<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\User;
class UserGallery extends Model
{
    protected $table = 'user_gallery';
    
    protected $fillable = ['user_id', 'filename', 'mime', 'original_filename'];
    
    public function user()
    {
        return $this->belongsTo('User');
    }
}
