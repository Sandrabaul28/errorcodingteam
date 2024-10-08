<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function index()
    {
        $plants = Plant::all(); // Adjust the pagination as needed
        return view('admin.plants.index', compact('plants'), [
            'title' => 'CROPS | Plants'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_of_plants' => 'required|string|max:255',

        ]);

        Plant::create([
            'name_of_plants' => $request->name_of_plants,
        ]);

        return redirect()->route('plants.index')->with('success', 'Plant added successfully!');
    }
}
