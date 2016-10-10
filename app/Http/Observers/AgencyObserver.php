<?php namespace App\Http\Observers;
use App\Http\Models\User;
class AgencyObserver {

    public function deleted($model)
    {
        //var_dump($model);exit;
    }
    public function deleting($model)
    {
        $users = User::where('agency_id','=',$model->id)->get();
        foreach($users as $user){
            User::destroy($user->id);
        }
    }

}