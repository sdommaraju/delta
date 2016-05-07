<?php namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Http\Models\Projects;
use App\Http\Models\Role;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['first_name','last_name','email', 'password','role_id','agency_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token','is_admin'];
	
	protected $casts = [
	    'is_admin' => 'boolean',
	];
	
	public function isAdmin()
	{
	    return $this->is_admin;
	}
	
	public function projects()
	{
	    return $this->hasMany('App\Http\Models\Projects');
	}
	
	public function agencyAdmin()
	{
	    return $this->belongsTo('Agency');
	}
	public function role()
	{
	    return $this->belongsTo('App\Http\Models\Role');
	}
}
