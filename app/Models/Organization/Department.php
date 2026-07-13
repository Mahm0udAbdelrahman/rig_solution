<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Organization\Employee;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'desc',
        'employee_id',
    ];

    public function employee()
    {
      return $this->belongsTo(Employee::class)->withDefault();
    }

    public function managers()
    {
        $relation = $this->belongsToMany(Employee::class);

        if ($this->employee_id) {
            $relation->wherePivot('employee_id', $this->employee_id);
        } else {
            $relation->whereRaw('1 = 0');
        }

        return $relation;
    }

    public function employees()
    {
        $relation = $this->belongsToMany(Employee::class);

        if ($this->employee_id) {
            $relation->wherePivot('employee_id', '!=', $this->employee_id);
        }

        return $relation;
    }

}
