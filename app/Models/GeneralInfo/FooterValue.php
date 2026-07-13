<?php

namespace App\Models\GeneralInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_no',
        'issue_no',
        'issue_date',
        'revision_no',
        'revision_date',
    ];
}
