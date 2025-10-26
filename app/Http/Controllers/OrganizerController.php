<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function dashboard()
    {
        return view('organizer.dashboard');
    }
}
