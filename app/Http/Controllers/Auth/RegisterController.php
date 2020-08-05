<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    protected function create(RegisterRequest $request)
    {
        User::create([
            'name'     => $request['name'],
            'email'    => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        return $this->sendResponse([], 'Successfully register');
    }
}
