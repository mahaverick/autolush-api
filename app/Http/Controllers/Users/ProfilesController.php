<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;

class ProfilesController extends Controller
{
    /**
     * Returns profile for authenticated user.
     *
     * @return [type] [description]
     */
    public function me()
    {
        return new UserResource(request()->user());
    }
}
