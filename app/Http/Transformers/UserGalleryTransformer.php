<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\UserGallery;

class UserGalleryTransformer extends TransformerAbstract {
    public function transform(UserGallery $gallery) {
        return [
            'id' => $gallery->id,
            'file_name' => $gallery->filename,
            'mime' => $gallery->mime,
            'user_id' => $gallery->user_id,
            'original_filename' => $gallery->original_filename
        ];
    }
}