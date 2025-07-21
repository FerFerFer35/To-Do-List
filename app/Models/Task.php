<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Specify the attributes that are mass assignable
    protected $fillable = ['title', 'description', 'completed'];
    // Define the attributes that should be hidden for arrays
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
