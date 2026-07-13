<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('mail_center_templates')) {
            Schema::create('mail_center_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('category')->default('general');
                $table->string('subject');
                $table->longText('body_html');
                $table->longText('body_text')->nullable();
                $table->boolean('is_active')->default(1);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('mail_center_templates')) {
            return;
        }

        $allowDataMutations = !app()->environment('production')
            || (bool) env('ALLOW_PROD_MIGRATION_DATA_PATCHES', false);

        if (!$allowDataMutations) {
            return;
        }

        $defaults = [
            [
                'name' => 'Quotation Delivery',
                'slug' => 'quotation-delivery',
                'category' => 'quotation',
                'subject' => 'Quotation for {{client_name}} - {{jcf_code}}',
                'body_html' => '<p>Dear {{contact_name}},</p><p>Please find our quotation related to JCF <strong>{{jcf_code}}</strong>.</p><p>If you need any clarification, please reply to this email.</p>',
                'body_text' => 'Dear {{contact_name}}, Please find our quotation related to JCF {{jcf_code}}.',
                'is_active' => 1,
            ],
            [
                'name' => 'Invoice Submission',
                'slug' => 'invoice-submission',
                'category' => 'invoice',
                'subject' => 'Invoice submission - {{client_name}} - {{jcf_code}}',
                'body_html' => '<p>Dear {{contact_name}},</p><p>Please find the invoice attached for JCF <strong>{{jcf_code}}</strong>.</p><p>Thank you.</p>',
                'body_text' => 'Dear {{contact_name}}, Please find the invoice attached for JCF {{jcf_code}}.',
                'is_active' => 1,
            ],
            [
                'name' => 'Inspection Report Delivery',
                'slug' => 'inspection-report-delivery',
                'category' => 'inspection',
                'subject' => 'Inspection report - {{client_name}} - {{jcf_code}}',
                'body_html' => '<p>Dear {{contact_name}},</p><p>Please find the generated inspection report for JCF <strong>{{jcf_code}}</strong>.</p>',
                'body_text' => 'Dear {{contact_name}}, Please find the generated inspection report for JCF {{jcf_code}}.',
                'is_active' => 1,
            ],
        ];

        foreach ($defaults as $template) {
            DB::table('mail_center_templates')->updateOrInsert(
                ['slug' => $template['slug']],
                array_merge($template, [
                    'updated_at' => now(),
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ])
            );
        }
    }

    public function down()
    {
        Schema::dropIfExists('mail_center_templates');
    }
};
