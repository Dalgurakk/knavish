<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['commercial', 'administrator']);
        $breadcrumb = array(
            0 => 'Dashboard'
        );
        $data['breadcrumb'] = $breadcrumb;
        $data['menuDashboard'] = 'selected';
        return view('home')->with($data);
    }
}
