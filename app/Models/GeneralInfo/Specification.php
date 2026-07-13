<?php

namespace App\Models\GeneralInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'desc',
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public static function getTubularSpecifications()
    {
        $ignoreList = [
            's-004',
            's-005',
            's-006',
            's-007',
            's-009',
        ];

//        return DB::table('specifications')->select('id', 'name', 'code')-->get();
        return Specification::query()->whereNotIn('code', $ignoreList)->select(['id', 'name', 'code'])->get();
    }

    public static function getNdtSpecifications()
    {
        $whiteList = [
            'S-002',
            'S-003',
            'S-005',
            'S-008',
            'S-009',
        ];

//        return DB::table('specifications')->select('id', 'name', 'code')-->get();
        return Specification::query()->whereIn('code', $whiteList)->select(['id', 'name', 'code'])->get();
    }
}
