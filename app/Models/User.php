<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Organization\Employee;
use App\Models\Organization\Role;
use App\Models\Session;
use App\Models\WorkFlow\MailCenterMailbox;
use App\Models\WorkFlow\MailCenterUserPreference;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'password',
        'role_id',
        'is_super_admin',
        'is_active',
        'last_active_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function findForPassport($username)
    {
        return $this->where('employee_id', $username)->first();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function isActive()
    {
        if($this->is_active == 1)
        {
            return '<span class="badge badge-success">Active</span>';
        }
        else
        {
            return '<span class="badge badge-danger">Not Active</span>';
        }
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isSuperAdmin(){
        if($this->is_super_admin == 1)
        {
            return 'Super Admin';
        }
    }

    public function hasPermission($module, $role)
    {
        if ((bool) $this->is_super_admin) {
            return $this->id;
        }

        if (!$this->role || !$this->role->roles) {
            return null;
        }

        foreach (json_decode($this->role->roles) as $value)
        {
            if ($value->modules === $module)
            {
                foreach ($value->roles as $value1)
                {
                    if($value1 === $role)
                    {
                        return $this->id;
                    }
                }
            }
        }
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.User.'.$this->id;
    }

    public function mailCenterMailboxes()
    {
        return $this->belongsToMany(MailCenterMailbox::class, 'mail_center_mailbox_user', 'user_id', 'mailbox_id')->withTimestamps();
    }

    public function mailCenterNotificationPreference()
    {
        return $this->hasOne(MailCenterUserPreference::class, 'user_id');
    }

}
