<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'role',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'created_at',
        'updated_at',
        'deleted_at',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified' => 'boolean',
        'mask' => 'boolean'
    ];

    protected $appends = [
        'email_verified',
        'full_name',
        'display_name',
        'full_id',
        'birth_date',
        'age',
        'years',
        'is_superadmin',
        'is_admin',
        'is_member',
        'is_applicant',
        'voted',
        'member_since_year',
    ];

    public function getIsSuperadminAttribute()
    {
        return $this->role == 'superadmin' ? true : false;
    }
    public function getIsAdminAttribute()
    {
        return $this->role == 'admin' || $this->role == 'superadmin' ? true : false;
    }
    public function getIsMemberAttribute()
    {
        return $this->role == 'member' || $this->role == 'admin' || $this->role == 'superadmin' ? true : false;
    }
    public function getIsApplicantAttribute()
    {
        return $this->role == 'applicant' ? true : false;
    }

    public function getMemberSinceYearAttribute()
    {
        return $this->member_since ? date_format(date_create($this->member_since),"Y") : null;
    }
    public function getBirthDateAttribute()
    {
        return $this->birth_at ? date_format(date_create($this->birth_at),"d/m/Y") : null;
    }
    public function getEmailVerifiedAttribute()
    {
        return $this->email_verified_at ? true : false;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullIdAttribute()
    {
        return "{$this->id_prefix}{$this->id_number}";
    }

    public function getAgeAttribute()
    {
        return $this->birth_at ? date_diff(date_create($this->birth_at), date_create(date("Y-m-d")))->format('%y') : null;
    }
    public function getYearsAttribute()
    {
        return $this->member_since ? date_diff(date_create($this->member_since), date_create(date("Y-m-d")))->format('%y') : null;
    }
    
    public function getDisplayNameAttribute()
    {
        $dname = explode(' ', $this->first_name);
        $dlast = explode(' ', $this->last_name);
        $displayName = $dname[0].' '.$dlast[0];
        
        return "{$dname[0]} {$dlast[0]}";
    }
    
    public function getVotedAttribute()
    {
        return VerifyUserVote::where('user_id', $this->id)->first() ? true : false;
    }

    public function userdata()
    {
        return $this->hasOne(UserData::class);
    }

    public function elections()
    {
        return $this->hasMany(VerifyUserVote::class);
    }
}
