<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpTracking extends Model
{
    protected $fillable = ['ip', 'date_attemp', 'today_attemp', 'total_attemp', 'status', 'expire_blocked_time', 'number_of_blocked_times'];
    use HasFactory;
}
