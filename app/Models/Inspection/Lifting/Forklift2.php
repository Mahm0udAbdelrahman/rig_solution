<?php

namespace App\Models\Inspection\Lifting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Inspection\Lifting\Forklift;
use App\Traits\HasInspectionLogo;

class Forklift2 extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
       'forklift_id',
       'lfr2_1',
       'lfr2_2',
       'lfr2_3',
       'lfr2_6',
       'lfr2_7',
       'lfr2_8',
       'lfr2_9',
       'lfr2_10',
       'lfr2_11',
       'lfr2_12',
       'lfr2_13',
       'lfr2_14',
       'lfr2_15',
       'lfr2_16',
       'lfr2_17',
       'lfr2_18',
       'lfr2_19',
       'lfr2_20',
       'lfr2_21',
       'lfr2_22',
       'lfr2_23',
       'lfr2_24',
       'lfr2_25',
       'lfr2_26',
       'lfr2_27',
       'lfr2_28',
       'lfr2_29',
       'lfr2_30',
       'lfr2_31',
       'lfr2_32',
       'lfr2_33',
       'lfr2_34',
       'lfr2_35',
       'lfr2_36',
       'lfr2_37',
       'lfr2_38',
       'lfr2_39',
       'lfr2_40',
       'lfr2_41',
       'lfr2_42',
     ];

     public function forklift()
     {
         return $this->belongsTo(Forklift::class);
     }

     public function satisfactory($satisfactory){
       if($satisfactory==='0'){
         echo "Satisfactory";
       }else{
         echo "Not Satisfactory";
       }
     }

     public function checkbox_yes($checkbox_id){
       if(str_contains((string) $checkbox_id, '_y')){
         return "checked";
       }
     }

     public function checkbox_no($checkbox_id){
       if(str_contains((string) $checkbox_id, '_n')){
         return "checked";
       }
     }
}
