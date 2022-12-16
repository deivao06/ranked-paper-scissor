<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserRepository
{
    public function getUserByEmail($email){
        return User::where('email', 'LIKE', '%'.trim($email).'%')->first();
    }

    public function store($request){
        if($this->getUserByEmail($request->get('email'))){
            $response = [
                "error" => true,
                "message" => "E-mail already in use"
            ];

            return $response;
        };

        $user = new User;

        $user->name = $request->get('username');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));

        if($user->save()){
            $response = [
                "error" => false,
                "message" => "User registered",
                "user" => $user
            ];
            return $response;
        }else{
            $response = [
                "error" => true,
                "message" => "Failed to register user",
            ];
            return $response;
        }
    }
}