<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Observers\AgencyObserver;

class Agency extends Model
{
    use SoftDeletes;
    
    protected $table = 'agencies';
    
    protected $fillable = ['name', 'logo_url', 'address','city','state','zip','email'];
    
    protected $dates = ['deleted_at'];
    
    public function userAdmin()
    {
        return $this->hasMany('App\Http\Models\User')->where('role_id','=','2');
    }
    
    public static function boot()
    {
        parent::boot();
    
        Agency::observe(new AgencyObserver);
    }
    
}
