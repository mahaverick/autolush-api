<?php

namespace App\Http\Controllers\Users\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;

class LoginController extends Controller
{
    /**
     * Logs in the user.
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:6|max:60',
        ]);

        $credentials = $request->only('email', 'password');

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'message'=> 'The given data was invalid.',
                'errors'=> [
                    'email' => [
                        'Could not sign you in using these credentials',
                    ],
                ],
            ], 422);
        }

        return (new UserResource($request->user()))
            ->additional([
                'meta' => [
                    'token' => $token,
                ],
            ]);
    }
}
