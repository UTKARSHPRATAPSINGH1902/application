<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'monthly_price', 'annual_price',
        'max_employees', 'storage_size', 'storage_unit', 'description'
    ];

    public function checklists()
    {
        return $this->belongsToMany(Checklist::class);
    }
}
