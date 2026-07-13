<?php

namespace Tests\Unit\Inspection;

use App\Models\Inspection\InspectionReport;
use App\Services\Inspection\InspectionReportLifecycleService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Fixtures\Inspection\FakeInspectionReportable;
use Tests\TestCase;

class InspectionReportLifecycleServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('fake_inspection_reportables')) {
            Schema::create('fake_inspection_reportables', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable();
            });
        }

        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        Schema::dropIfExists('fake_inspection_reportables');

        parent::tearDown();
    }

    public function test_duplicate_record_starts_without_approval(): void
    {
        $service = app(InspectionReportLifecycleService::class);

        $sourceOwner = FakeInspectionReportable::query()->create(['code' => '001']);
        $sourceReport = $sourceOwner->report()->create([
            'job_request_id' => 10,
            'code' => '001',
            'status' => 1,
            'publish' => null,
            'user_id' => 3,
            'user_id_approved' => 17,
        ]);

        $duplicateOwner = FakeInspectionReportable::query()->create(['code' => '001 - Duplicated']);
        $duplicateReport = $service->createDuplicateRecord($sourceReport, $duplicateOwner, 5);

        $this->assertSame('001 - Duplicated', $duplicateReport->code);
        $this->assertNull($duplicateReport->user_id_approved);
        $this->assertNull($duplicateReport->publish);
        $this->assertSame(5, (int) $duplicateReport->user_id);
    }

    public function test_duplicate_record_does_not_inherit_legacy_published_fallback_approval(): void
    {
        $service = app(InspectionReportLifecycleService::class);

        $sourceOwner = FakeInspectionReportable::query()->create(['code' => '001']);
        $sourceReport = $sourceOwner->report()->create([
            'job_request_id' => 11,
            'code' => '001',
            'status' => 1,
            'publish' => null,
            'user_id' => 2,
            'user_id_edit' => null,
            'user_id_approved' => null,
        ]);
        DB::table('inspection_reports')
            ->where('id', $sourceReport->id)
            ->update([
                'publish' => 1,
                'user_id_edit' => 9,
            ]);
        $sourceReport->refresh();

        $duplicateOwner = FakeInspectionReportable::query()->create(['code' => '001 - Duplicated']);
        $duplicateReport = $service->createDuplicateRecord($sourceReport, $duplicateOwner, 4);

        $this->assertNull($duplicateReport->user_id_approved);
    }

    public function test_duplicate_record_does_not_inherit_source_creator_when_source_is_not_approved(): void
    {
        $service = app(InspectionReportLifecycleService::class);

        $sourceOwner = FakeInspectionReportable::query()->create(['code' => '001']);
        $sourceReport = $sourceOwner->report()->create([
            'job_request_id' => 111,
            'code' => '001',
            'status' => 1,
            'publish' => null,
            'user_id' => 13,
            'user_id_edit' => null,
            'user_id_approved' => null,
        ]);

        $duplicateOwner = FakeInspectionReportable::query()->create(['code' => '001 - Duplicated']);
        $duplicateReport = $service->createDuplicateRecord($sourceReport, $duplicateOwner, 4);

        $this->assertNull($duplicateReport->user_id_approved);
    }

    public function test_first_edit_of_duplicated_clone_keeps_duplicate_code_and_pending_approval_until_publish(): void
    {
        $service = app(InspectionReportLifecycleService::class);

        FakeInspectionReportable::query()->create(['code' => '001'])
            ->report()
            ->create([
                'job_request_id' => 12,
                'code' => '001',
                'status' => 1,
                'publish' => 1,
                'user_id_approved' => 7,
            ]);

        FakeInspectionReportable::query()->create(['code' => '002'])
            ->report()
            ->create([
                'job_request_id' => 12,
                'code' => '002',
                'status' => 1,
                'publish' => 1,
                'user_id_approved' => 7,
            ]);

        $duplicateOwner = FakeInspectionReportable::query()->create(['code' => '002 - Duplicated']);
        $duplicateReport = $duplicateOwner->report()->create([
            'job_request_id' => 12,
            'code' => '002 - Duplicated',
            'status' => 1,
            'publish' => null,
            'user_id_approved' => null,
        ]);

        $persisted = $service->persistForOwner($duplicateOwner, [
            'reportable_id' => $duplicateOwner->id,
            'reportable_type' => FakeInspectionReportable::class,
            'user_id_edit' => 22,
            'publish' => null,
        ]);

        $duplicateReport->refresh();
        $duplicateOwner->refresh();

        $this->assertTrue($persisted);
        $this->assertSame('002 - Duplicated', $duplicateReport->code);
        $this->assertSame('002 - Duplicated', $duplicateOwner->code);
        $this->assertNull($duplicateReport->user_id_approved);
        $this->assertNull($duplicateReport->publish);
    }

    public function test_publishing_duplicated_clone_reassigns_natural_code_and_returns_to_approval_queue(): void
    {
        $service = app(InspectionReportLifecycleService::class);

        FakeInspectionReportable::query()->create(['code' => '001'])
            ->report()
            ->create([
                'job_request_id' => 14,
                'code' => '001',
                'status' => 1,
                'publish' => 1,
                'user_id_approved' => 7,
            ]);

        FakeInspectionReportable::query()->create(['code' => '002'])
            ->report()
            ->create([
                'job_request_id' => 14,
                'code' => '002',
                'status' => 1,
                'publish' => 1,
                'user_id_approved' => 7,
            ]);

        $duplicateOwner = FakeInspectionReportable::query()->create(['code' => '002 - Duplicated']);
        $duplicateReport = $duplicateOwner->report()->create([
            'job_request_id' => 14,
            'code' => '002 - Duplicated',
            'status' => 1,
            'publish' => null,
            'user_id_approved' => 7,
        ]);

        $persisted = $service->persistForOwner($duplicateOwner, [
            'reportable_id' => $duplicateOwner->id,
            'reportable_type' => FakeInspectionReportable::class,
            'publish' => 1,
            'user_id_edit' => null,
        ]);

        $duplicateReport->refresh();
        $duplicateOwner->refresh();

        $this->assertTrue($persisted);
        $this->assertSame('003', $duplicateReport->code);
        $this->assertSame('003', $duplicateOwner->code);
        $this->assertNull($duplicateReport->user_id_approved);
        $this->assertNull($duplicateReport->publish);
    }

    public function test_mark_published_preserves_existing_approval(): void
    {
        $service = app(InspectionReportLifecycleService::class);

        $owner = FakeInspectionReportable::query()->create(['code' => '004']);
        $report = $owner->report()->create([
            'job_request_id' => 13,
            'code' => '004',
            'status' => 1,
            'publish' => null,
            'user_id_approved' => 18,
        ]);

        $published = $service->markPublished($report);
        $report->refresh();

        $this->assertTrue($published);
        $this->assertSame(1, (int) $report->publish);
        $this->assertSame(18, (int) $report->user_id_approved);
    }

    public function test_publishing_duplicated_clone_with_missing_approval_still_returns_to_approval_queue(): void
    {
        $service = app(InspectionReportLifecycleService::class);

        FakeInspectionReportable::query()->create(['code' => '001'])
            ->report()
            ->create([
                'job_request_id' => 15,
                'code' => '001',
                'status' => 1,
                'publish' => 1,
                'user_id_approved' => 5,
            ]);

        $duplicateOwner = FakeInspectionReportable::query()->create(['code' => '001 - Duplicated']);
        $duplicateReport = $duplicateOwner->report()->create([
            'job_request_id' => 15,
            'code' => '001 - Duplicated',
            'status' => 1,
            'publish' => null,
            'user_id' => 9,
            'user_id_edit' => null,
            'user_id_approved' => null,
        ]);

        $published = $service->persistForOwner($duplicateOwner, [
            'publish' => 1,
            'user_id_edit' => null,
        ]);

        $duplicateReport->refresh();

        $this->assertTrue($published);
        $this->assertNull($duplicateReport->publish);
        $this->assertNull($duplicateReport->user_id_approved);
    }
}
