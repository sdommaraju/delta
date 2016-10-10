<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Observers\AgencyObserver;

class VendorGroup extends Model
{
    use SoftDeletes;
    
    protected $table = 'vendor_groups';
    
    protected $fillable = ['name', 'description','agency_id'];
    
    protected $dates = ['deleted_at'];
    
}
