<?php

namespace App\Models\Inspection\Lifting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Inspection\Lifting\Crane;
use App\Traits\HasInspectionLogo;

class Crane2 extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
				'crane_id',
				'lcr2_1',
				'lcr2_2',
				'lcr2_3',
				'lcr2_4',
				'lcr2_5',
				'lcr2_6',
				'lcr2_7',
				'lcr2_8',
				'lcr2_9',
				'lcr2_10',
				'lcr2_11',
				'lcr2_12',
				'lcr2_13',
				'lcr2_14',
				'lcr2_15',
				'lcr2_16',
				'lcr2_17',
				'lcr2_18',
				'lcr2_19',
				'lcr2_20',
				'lcr2_21',
				'lcr2_22',
				'lcr2_23',
				'lcr2_24',
				'lcr2_25',
				'lcr2_26',
				'lcr2_27',
				'lcr2_28',
				'lcr2_29',
				'lcr2_30',
				'lcr2_31',
				'lcr2_32',
				'lcr2_33',
				'lcr2_34',
				'lcr2_35',
				'lcr2_36',
				'lcr2_37',
				'lcr2_38',
				'lcr2_39',
				'lcr2_40',
				'lcr2_41',
				'lcr2_42',
				'lcr2_43',
    ];

    public function crane()
    {
        return $this->belongsTo(Crane::class);
    }
}
