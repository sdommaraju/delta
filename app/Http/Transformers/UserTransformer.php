<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\User;

class UserTransformer extends TransformerAbstract {
    public function transform(User $user) {
        return [
            'id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email
        ];
    }
}