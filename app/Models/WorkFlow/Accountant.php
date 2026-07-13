<?php

namespace App\Models\WorkFlow;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accountant extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'job_request_id',
        'invoice_id',
        'payment_id',
        'posting_date',
        'entry_type',
        'debit_account_id',
        'credit_account_id',
        'amount',
        'currency',
        'reference_no',
        'status',
        'is_posted',
        'note',
        'user_id',
        'approved_by',
        'approved_at',
        'sync',
        'updated',
    ];

    protected $casts = [
        'posting_date' => 'date',
        'approved_at' => 'datetime',
        'is_posted' => 'integer',
        'amount' => 'decimal:2',
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'job_request_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function debitAccount()
    {
        return $this->belongsTo(ChartAccount::class, 'debit_account_id');
    }

    public function creditAccount()
    {
        return $this->belongsTo(ChartAccount::class, 'credit_account_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function lines()
    {
        return $this->hasMany(AccountantEntryLine::class, 'accountant_id')
            ->orderBy('line_order')
            ->orderBy('id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function audits()
    {
        return $this->hasMany(AccountantAudit::class, 'accountant_id');
    }

    public static function nextTreeSequentialCode(
        ?int $debitAccountId = null,
        ?int $creditAccountId = null,
        ?string $postingDate = null,
        array $entryLines = []
    ): string {
        $primaryAccountId = self::resolvePrimaryAccountId($entryLines, $debitAccountId, $creditAccountId);
        $rootCode = self::resolveRootAccountCode($primaryAccountId);
        $yearToken = self::resolveYearToken($postingDate);

        $prefix = 'ACC-'.$rootCode.'-'.$yearToken.'-';
        $next = self::resolveNextSequenceForPrefix($prefix);

        return $prefix.str_pad((string)$next, 4, '0', STR_PAD_LEFT);
    }

    private static function resolvePrimaryAccountId(array $entryLines, ?int $debitAccountId, ?int $creditAccountId): int
    {
        foreach ($entryLines as $line) {
            if (!is_array($line)) {
                continue;
            }

            $lineType = strtolower(trim((string)($line['line_type'] ?? '')));
            $accountId = (int)($line['chart_account_id'] ?? 0);
            if ($lineType === 'debit' && $accountId > 0) {
                return $accountId;
            }
        }

        foreach ($entryLines as $line) {
            if (!is_array($line)) {
                continue;
            }

            $accountId = (int)($line['chart_account_id'] ?? 0);
            if ($accountId > 0) {
                return $accountId;
            }
        }

        if ((int)$debitAccountId > 0) {
            return (int)$debitAccountId;
        }

        if ((int)$creditAccountId > 0) {
            return (int)$creditAccountId;
        }

        return 0;
    }

    private static function resolveRootAccountCode(int $accountId): string
    {
        if ($accountId <= 0) {
            return 'GEN';
        }

        $account = ChartAccount::query()
            ->select(['id', 'parent_id', 'code'])
            ->find($accountId);
        if (!$account) {
            return 'GEN';
        }

        $rootCode = trim((string)$account->code);
        $guard = 0;
        while (!empty($account->parent_id) && $guard < 20) {
            $parent = ChartAccount::query()
                ->select(['id', 'parent_id', 'code'])
                ->find((int)$account->parent_id);
            if (!$parent) {
                break;
            }

            $account = $parent;
            $rootCode = trim((string)$account->code);
            $guard++;
        }

        $normalized = strtoupper((string)preg_replace('/[^A-Za-z0-9]/', '', $rootCode));
        if ($normalized === '') {
            return 'GEN';
        }

        return substr($normalized, 0, 10);
    }

    private static function resolveYearToken(?string $postingDate): string
    {
        try {
            if ($postingDate !== null && trim((string)$postingDate) !== '') {
                return Carbon::parse($postingDate)->format('y');
            }
        } catch (\Throwable $e) {
            // fallback to current year
        }

        return now()->format('y');
    }

    private static function resolveNextSequenceForPrefix(string $prefix): int
    {
        $codes = self::query()
            ->where('code', 'like', $prefix.'%')
            ->pluck('code');

        $max = 0;
        foreach ($codes as $code) {
            $code = (string)$code;
            if (preg_match('/(\d+)$/', $code, $matches)) {
                $max = max($max, (int)$matches[1]);
            }
        }

        return $max + 1;
    }
}
