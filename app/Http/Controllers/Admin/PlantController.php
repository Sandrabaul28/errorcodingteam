<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use App\Models\PlantVariety;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function index()
    {
        $plants = Plant::all();
        $plants = Plant::with('varieties')->get(); // Adjust the pagination as needed
        return view('admin.plants.index', compact('plants'), [
            'title' => 'CROPS | Plants'
        ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name_of_plants' => 'required|string|max:255',
            'variety_name' => 'required|string|max:255', // Changed validation for variety
        ]);

        // Create the plant
        $plant = Plant::create([
            'name_of_plants' => $request->name_of_plants,
        ]);

        // Save the single variety
        PlantVariety::create([
            'plant_id' => $plant->id,
            'variety_name' => $request->variety_name, // Save the variety name
        ]);

        return redirect()->back()->with('success', 'Plant added successfully!');
    }


}
