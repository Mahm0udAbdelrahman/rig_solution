<?php

namespace App\Models\WorkFlow;

use App\Models\Persons\ClientDepartment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JcfStatus;
use App\Models\Persons\Client;
use App\Models\Persons\Supplier;
use App\Models\Persons\ContactPerson;
use App\Models\WorkFlow\Payment;
use App\Models\WorkFlow\Inventory;
use App\Models\WorkFlow\Accountant;

use App\Models\Inspection\InspectionReport;

use App\Models\Organization\Department;
use App\Models\Organization\Employee;

use App\Models\User;

class JobRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'purchase_order',
        'jcf_ref',
        'client_id',
        'supplier_id',
        'contact_people_id',
        'client_department_id',
        'subject',
        'work_location',
        'contactway',
        'user_id',
        'job_requierd_details',
        'contact_date',
        'managers',
        'tools',
        'scope_of_work',
        'specification',
        'deploc',
        'sync',
        'updated',
    ];

		public function jcf_status()
    {
      	return $this->hasOne(JcfStatus::class);
    }

    public function client()
    {
      	return $this->belongsTo(Client::class, 'client_id');
    }

    public function supplier()
    {
      	return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function contactPeopleShow()
    {
      	return $this->belongsTo(ContactPerson::class, 'contact_people_id');
    }

    public function clientDepartment()
    {
      	return $this->belongsTo(ClientDepartment::class, 'client_department_id');
    }

    public function departments()
    {
      	return $this->belongsToMany(Department::class);
    }

    public function employees()
    {
      	return $this->belongsToMany(Employee::class);
    }

    public function qutation()
    {
      	return $this->hasOne(Qutation::class);
    }

    public function invoice()
    {
      	return $this->hasOne(Invoice::class);
    }

    public function user()
    {
      	return $this->belongsTo(User::class, 'user_id');
    }

    public function serviceTicket()
    {
      	return $this->hasOne(ServiceTicket::class);
    }

    public function packingSlip()
    {
      	return $this->hasOne(PackingSlip::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function accountants()
    {
        return $this->hasMany(Accountant::class);
    }

    public function mailMessages()
    {
        return $this->hasMany(MailCenter::class, 'job_request_id');
    }

    // public function crane()
    // {
    //   	return $this->belongsTo(Crane::class);
    // }
		//
    public function inspection_reports()
    {
      	return $this->hasMany(InspectionReport::class);
    }
		//
    // public function cranes()
    // {
    //   	return $this->hasMany(Crane::class);
    // }
		//
    // public function overheadcrane()
    // {
    //   	return $this->belongsTo(OverheadCrane::class);
    // }
		//
    // public function overheadcranes()
    // {
    //   	return $this->hasMany(OverheadCrane::class);
    // }
		//
    // public function forklift()
    // {
    //   	return $this->belongsTo(Forklift::class);
    // }
		//
    // public function forklifts()
    // {
    //   	return $this->hasMany(Forklift::class);
    // }
		//
    // public function throughexamination()
    // {
    //   	return $this->belongsTo(ThroughExamination::class);
    // }
		//
    // public function throughexaminations()
    // {
    //   	return $this->hasMany(ThroughExamination::class);
    // }

}
