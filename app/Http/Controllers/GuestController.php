<?php

namespace App\Http\Controllers;

class GuestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:guests');
    }

    public function allUsers()
    {
        return (new ListController())->allUsers();
    }

}