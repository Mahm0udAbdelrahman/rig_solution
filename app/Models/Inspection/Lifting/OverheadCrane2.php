<?php

namespace App\Models\Inspection\Lifting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Inspection\Lifting\OverheadCrane;
use App\Traits\HasInspectionLogo;

class OverheadCrane2 extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
       'overhead_crane_id',
       'locr2_1',
       'locr2_2',
       'locr2_3',
       'locr2_4',
       'locr2_5',
       'locr2_6',
       'locr2_7',
       'locr2_8',
       'locr2_9',
       'locr2_10',
       'locr2_11',
       'locr2_12',
       'locr2_13',
       'locr2_14',
       'locr2_15',
       'locr2_16',
       'locr2_17',
       'locr2_18',
       'locr2_19',
       'locr2_20',
       'locr2_21',
       'locr2_22',
       'locr2_23',
       'locr2_24',
       'locr2_25',
       'locr2_26',
       'locr2_27',
       'locr2_28',
       'locr2_29',
       'locr2_30',
       'locr2_31',
       'locr2_32',
       'locr2_33',
       'locr2_34',
       'locr2_35',
       'locr2_36',
       'locr2_37',
       'locr2_38',
       'locr2_39',
       'locr2_40',
       'locr2_41',
       'locr2_42',
       'locr2_43',
    ];

    public function overheadCrane()
    {
        return $this->belongsTo(OverheadCrane::class);
    }
}
