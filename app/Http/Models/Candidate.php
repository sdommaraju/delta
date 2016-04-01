<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'candidates';
    
    protected $fillable = ['agency_id', 'first_name', 'last_name','address1','city','state','zip','phone_number','email','description','resume'];
    
    
}
