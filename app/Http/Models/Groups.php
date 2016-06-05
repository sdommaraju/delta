<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
     protected $table = 'groups';
    
    protected $fillable = ['name', 'agency_id'];
}
