<?php

namespace App\Models\WorkFlow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\Payment;
use App\Models\WorkFlow\Accountant;
use App\Models\User;

use NumberFormatter;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
	      'invoice_company_type',
	      'code',
	      'cpo',
	      'contract',
	      'job_request_id',
	      'items',
	      'terms',
	      'sub_total',
	      'discount_type',
	      'discount_value',
	      'discount_amount',
	      'tax',
	      'withholding',
	      'total',
	      'type',
	      'user_id',
	      'sync',
	      'updated',
    ];

		// invoice company types static
		public static $INVOICE_RSE_TYPE = 'RSE';
		public static $INVOICE_LTD_TYPE = 'LTD';

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'job_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('user_id_edit', 'user_id_approved');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }

    public function accountants()
    {
        return $this->hasMany(Accountant::class, 'invoice_id');
    }

    function numberTowords($num)
    {
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $f1 = new NumberFormatter("en", NumberFormatter::DECIMAL_ALWAYS_SHOWN);
        if (strpos($f->format($num), "point") != '')
				{
	          $var = substr($f->format($num), 0, strpos($f->format($num), "point"));
	          $var .= ' & '.str_replace('.', '', strstr($f1->format($num), '.')).' / 100';
        }
				else
				{
          	$var = $f->format($num);
        }
        return $var;
    }
}
