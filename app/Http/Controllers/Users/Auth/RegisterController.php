<?php

namespace App\Http\Controllers\Users\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Users\UserResource;

class RegisterController extends Controller
{
    /**
     * Register new User.
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|max:60',
            'name' => 'required',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return new UserResource($user);
    }
}
