<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $table = 'agencies';
    
    protected $fillable = ['name', 'logo_url', 'address','city','state','zip','email'];
    
    
}
