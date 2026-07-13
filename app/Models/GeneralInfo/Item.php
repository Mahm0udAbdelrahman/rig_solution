<?php

namespace App\Models\GeneralInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
      'code',
      'name',
      'priceInEgp',
      'priceInDollar',
      'desc',
    ];

}
