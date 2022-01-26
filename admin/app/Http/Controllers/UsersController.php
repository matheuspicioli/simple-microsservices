<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function random()
    {
        $user = User::inRandomOrder()->first();

        return [
            'id' => $user->id
        ];
    }
}
