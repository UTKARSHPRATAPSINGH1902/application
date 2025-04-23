<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'package_id',
        'subscription_date',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
  
}
