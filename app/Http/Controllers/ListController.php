<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function allUsers()
    {
         return response()->json(['users' =>  User::all()], 200);
    }
}
