<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;



class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }
}
