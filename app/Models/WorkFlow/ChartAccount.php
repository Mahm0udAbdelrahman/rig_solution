<?php

namespace App\Models\WorkFlow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartAccount extends Model
{
    use HasFactory;

    public const TYPE_LABELS = [
        'asset' => ['en' => 'Asset', 'ar' => 'أصل'],
        'liability' => ['en' => 'Liability', 'ar' => 'التزام'],
        'equity' => ['en' => 'Equity', 'ar' => 'حقوق ملكية'],
        'revenue' => ['en' => 'Revenue', 'ar' => 'إيراد'],
        'expense' => ['en' => 'Expense', 'ar' => 'مصروف'],
    ];

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'name_ar',
        'type',
        'parent_id',
        'level',
        'is_active',
        'note',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('code');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function debitEntries()
    {
        return $this->hasMany(Accountant::class, 'debit_account_id');
    }

    public function creditEntries()
    {
        return $this->hasMany(Accountant::class, 'credit_account_id');
    }

    public static function typeOptions(): array
    {
        $options = [];
        foreach (self::TYPE_LABELS as $type => $labels) {
            $options[$type] = $labels['en'].' / '.$labels['ar'];
        }

        return $options;
    }

    public function getDisplayNameAttribute(): string
    {
        $englishName = trim((string) ($this->name_en ?: $this->name ?: ''));
        $arabicName = trim((string) ($this->name_ar ?: ''));

        if ($englishName !== '' && $arabicName !== '' && $englishName !== $arabicName) {
            return $englishName.' / '.$arabicName;
        }

        return $englishName !== '' ? $englishName : $arabicName;
    }

    public function getTypeLabelAttribute(): string
    {
        $labels = self::TYPE_LABELS[$this->type] ?? null;
        if (!$labels) {
            return ucfirst((string) $this->type);
        }

        return $labels['en'].' / '.$labels['ar'];
    }
}
