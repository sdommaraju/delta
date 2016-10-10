<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Observers\AgencyObserver;

class Vendor extends Model
{
    use SoftDeletes;
    
    protected $table = 'vendors';
    
    protected $fillable = ['name', 'email_id','agency_id','vendor_group_id'];
    
    protected $dates = ['deleted_at'];
    
    public function group()
    {
    	return $this->belongsTo('App\Http\Models\VendorGroup','vendor_group_id','id');
    }
    
}
