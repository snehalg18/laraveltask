<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $User= User::count();

        return view('dashboard.dashboard', [
            'user' => $User,
        ]);
    }

    public function emp_dashboard(Request $request)
    {
        $user = auth()->user(); 
        $totalUsers = User::count();  
        return view('dashboard.emp_dashboard', [
            'user' => $user,        
            'totalUsers' => $totalUsers,
        ]);
    }
    
}
