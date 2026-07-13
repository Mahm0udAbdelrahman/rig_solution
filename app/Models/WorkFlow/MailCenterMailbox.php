<?php

namespace App\Models\WorkFlow;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class MailCenterMailbox extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'driver',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'from_email',
        'from_name',
        'reply_to_email',
        'reply_to_name',
        'is_default',
        'is_active',
        'note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function messages()
    {
        return $this->hasMany(MailCenter::class, 'mailbox_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'mail_center_mailbox_user', 'mailbox_id', 'user_id')->withTimestamps();
    }

    public function scopeVisibleToUser(Builder $query, ?User $user, bool $activeOnly = false): Builder
    {
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($activeOnly) {
            $query->where('is_active', 1);
        }

        if ($user->isSuperAdmin()) {
            return $query;
        }

        if (!static::assignmentsExist()) {
            return $query;
        }

        return $query->whereHas('users', static function (Builder $builder) use ($user) {
            $builder->where('users.id', $user->id);
        });
    }

    public static function assignmentsExist(): bool
    {
        return DB::table('mail_center_mailbox_user')->exists();
    }

    public static function visibleIdsForUser(?User $user, bool $activeOnly = false): array
    {
        return static::query()
            ->visibleToUser($user, $activeOnly)
            ->pluck('id')
            ->map(static fn ($id) => (int) $id)
            ->all();
    }

    public function isVisibleToUser(?User $user): bool
    {
        return in_array($this->id, static::visibleIdsForUser($user), true);
    }

    public function setPasswordAttribute($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            $this->attributes['password'] = '';
            return;
        }

        try {
            Crypt::decryptString($value);
            $this->attributes['password'] = $value;
        } catch (\Throwable $e) {
            $this->attributes['password'] = Crypt::encryptString($value);
        }
    }

    public function getPasswordAttribute($value)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }
}
