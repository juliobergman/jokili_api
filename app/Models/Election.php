<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Election extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'name',
        'status',
        'start',
        'end',
    ];
    protected $casts = [
        'start' => 'datetime:d-m-Y h:i a',
        'end' => 'datetime:d-m-Y h:i a'
        
    ];
    protected $appends = [
        'time_zone',
        'current_time',
        'active'
    ];

    protected function check_in_range($start_date, $end_date, $date_from_user)
    {
      // Convert to timestamp
      $start_ts = strtotime($start_date);
      $end_ts = strtotime($end_date);
      $user_ts = strtotime($date_from_user);
    
      // Check that user date is between start & end
      return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

    public function getActiveAttribute()
    {
        return $this->check_in_range($this->start, $this->end,$this->current_time);
        // return $this->time_zone;
    }
    public function getCurrentTimeAttribute()
    {
        return date_format(Carbon::now(),'d-m-Y h:i a');
    }
    public function getTimeZoneAttribute()
    {
        return date_default_timezone_get();
    }

}
