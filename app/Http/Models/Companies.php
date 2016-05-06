<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    protected $table = 'companies';
    
    protected $fillable = ['name', 'city','state','contact_name','contact_email'];
    
  
}
