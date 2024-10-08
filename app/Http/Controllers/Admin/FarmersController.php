<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Affiliation; // Import the Affiliation model
use Illuminate\Http\Request;

class FarmersController extends Controller
{
    public function index()
    {
        // Fetch all farmers
        $farmers = Farmer::with('affiliation')->get();

        // Fetch all affiliations
        $affiliations = Affiliation::all();

        // Pass both farmers and affiliations to the view
        return view('admin.farmers.index', compact('farmers', 'affiliations'));
    }


    public function create()
    {
        // Fetch all affiliations
        $affiliations = Affiliation::all();
        
        // Fetch all farmers for the create view
        $farmers = Farmer::with('affiliation')->get();

        // Pass affiliations and farmers to the create view
        return view('admin.farmers.create', compact('affiliations', 'farmers'), [
            'title' => 'Add Farmer'
        ]);
    }


    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension' => 'nullable|string|max:10',
            'affiliation_id' => 'required|exists:affiliations,id',
        ]);

        // Create a new farmer
        $farmer = Farmer::create($request->all());

        // Redirect back with the input data and a success message
        return redirect()->route('farmers.create')->with([
            'success' => 'Farmer added successfully!',
            'input' => $request->all() // Pass the input data back to the view
        ]);
    }

    public function show(Farmer $farmer)
    {
        return response()->json($farmer->load('affiliation'));
    }

    public function update(Request $request, $id)
    {
        // Hanapin ang farmer record
        $farmer = Farmer::findOrFail($id);

        // I-update ang farmer fields
        $farmer->first_name = $request->first_name;
        $farmer->last_name = $request->last_name;
        $farmer->middle_name = $request->middle_name;
        $farmer->extension = $request->extension;
        $farmer->affiliation_id = $request->affiliation_id;
        $farmer->save(); // I-save ang mga pagbabago

        return redirect()->back()->with('success', 'Farmer updated successfully!');
    }

    // Delete Farmer
    public function destroy($id)
    {
        // Hanapin at i-delete ang farmer record
        $farmer = Farmer::findOrFail($id);
        $farmer->delete();

        return redirect()->back()->with('success', 'Farmer deleted successfully!');
    }

}
