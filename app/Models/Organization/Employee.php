<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Models\Organization\Department;
use App\Models\User;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'email',
        'tel',
        //'salery',
        //'department_id',
        'esign',
        'avatar',
        'desc',
        'type',
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return null;
        }

        $relativePath = 'employees/avatars/' . $this->avatar;
        if (!self::ensurePublicStorageMirror($relativePath)) {
            return null;
        }

        return asset('storage/' . $relativePath);
    }

    public function getEsignUrlAttribute()
    {
        if (!$this->esign) {
            return null;
        }

        $relativePath = 'employees/' . $this->esign;
        if (!self::ensurePublicStorageMirror($relativePath)) {
            return null;
        }

        return asset('storage/' . $relativePath);
    }

    public function getAvatarInitialsAttribute()
    {
        $name = trim((string) $this->name);

        if ($name === '') {
            return 'U';
        }

        $segments = preg_split('/\s+/', $name) ?: [];
        $initials = collect($segments)
            ->filter()
            ->take(2)
            ->map(function ($segment) {
                return mb_strtoupper(mb_substr($segment, 0, 1));
            })
            ->implode('');

        return $initials ?: 'U';
    }

    private static function ensurePublicStorageMirror(string $relativePath): bool
    {
        $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
        if ($relativePath === '') {
            return false;
        }

        $publicFile = public_path('storage/' . $relativePath);
        if (is_file($publicFile)) {
            return true;
        }

        $sourceFile = storage_path('app/public/' . $relativePath);
        if (!is_file($sourceFile)) {
            return false;
        }

        $targetDir = dirname($publicFile);
        if (!is_dir($targetDir)) {
            File::makeDirectory($targetDir, 0755, true, true);
        }

        File::copy($sourceFile, $publicFile);

        return is_file($publicFile);
    }
}
