<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $table = 'timesheets';
    
    protected $fillable = ['user_id', 'project_id', 'hours', 'task'];
    
    public function user()
    {
        return $this->belongsTo('User');
    }
}
