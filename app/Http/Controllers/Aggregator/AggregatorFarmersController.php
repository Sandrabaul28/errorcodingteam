<?php

namespace App\Http\Controllers\Aggregator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Affiliation; // Import the Affiliation model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AggregatorFarmersController extends Controller
{
    public function index()
    {
        // Fetch all farmers
        $farmers = Farmer::with('affiliation')->get();

        // Fetch all affiliations
        $affiliations = Affiliation::all();

        // Pass both farmers and affiliations to the view
        return view('aggregator.farmers.index', compact('farmers', 'affiliations'));
    }


    public function create()
    {
        // Fetch all affiliations
        $affiliations = Affiliation::all();
        
        // Fetch all farmers for the create view
        $farmers = Farmer::with('affiliation')->get();

        // Pass affiliations and farmers to the create view
        return view('aggregator.farmers.create', compact('affiliations', 'farmers'), [
            'title' => 'Add Farmer'
        ]);
    }


    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension' => 'nullable|string|max:10',
            'affiliation_id' => 'required|exists:affiliations,id',
            'email' => 'required|email|unique:users,email', // Ensure email is unique in users table
            'password' => 'required|string|min:8|confirmed', // Password confirmation must match
        ]);

        // Create a new user for the farmer with role_id = 2 (User role)
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'affiliation_id' => $request->affiliation_id,
            'password' => Hash::make($request->password), // Hash the password before saving
            'role_id' => 2, // Assign the "User/Farmer Aggregator" role
        ]);

        // Create a new farmer and assign the newly created user's ID as 'user_id'
        Farmer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'extension' => $request->extension,
            'affiliation_id' => $request->affiliation_id,
            'user_id' => $user->id, // Link to the user created
            'added_by' => auth()->user()->id, // Set the added_by to the logged-in user's ID
        ]);

        // Redirect back to the farmer creation page with a success message
        return redirect()->route('farmers.create')->with([
            'success' => 'Farmer and User created successfully!',
            'input' => $request->all() // Pass the input data back to the view for display
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
