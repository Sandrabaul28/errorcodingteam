<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{ 

    public function index()
    {
        // Retrieve the total number of users
        $totalUsers = User::count(); // Make sure the User model is correctly referenced

        return view('admin.dashboard.index', compact('totalUsers'), [
            'title' => 'Admin | Dashboard'
        ]); // Passing the variable to the view
    }
}
