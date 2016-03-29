<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\User;
class Projects extends Model
{
    protected $table = 'projects';
    
    protected $fillable = ['user_id', 'project_name', 'client'];
    
    public function user()
    {
        return $this->belongsTo('User');
    }
}
