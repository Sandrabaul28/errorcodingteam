<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryValuedCrop; 
use App\Models\Farmer;
use App\Models\Plant; // Assuming you have a Plant model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountController extends Controller
{
    public function count()
    {
        $inventoryCrops = DB::table('inventory_valued_crops')
            ->join('farmers', 'inventory_valued_crops.farmer_id', '=', 'farmers.id')
            ->join('plants', 'inventory_valued_crops.plant_id', '=', 'plants.id')
            ->join('affiliations', 'farmers.affiliation_id', '=', 'affiliations.id')
            ->select(
                'farmers.first_name',
                'farmers.last_name',
                'affiliations.name_of_association',
                'affiliations.name_of_barangay',
                'inventory_valued_crops.farmer_id',
                DB::raw('GROUP_CONCAT(plants.name_of_plants, " (", inventory_valued_crops.count, ")") as plants_counts')
            )
            ->groupBy('inventory_valued_crops.farmer_id')
            ->get();

        $farmers = Farmer::all(); // Get all farmers for the form
        $plants = Plant::all(); // Get all plants for the form

        // Return the view with inventory data
        return view('admin.hvcdp.count', compact('inventoryCrops', 'farmers', 'plants'), [
            'title' => 'HVCDP - Counts'
        ]);
    }

    // Store a new inventory crop
    public function store(Request $request)
    {
        $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'plant_id' => 'required|exists:plants,id',
            'count' => 'required|integer|min:1',
        ]);

        // Create a new inventory valued crop
        InventoryValuedCrop::create($request->all());

        // Redirect to the count view with success message
        return redirect()->route('admin.hvcdp.count')->with('success', 'Crop added successfully.');
    }

    // Show the form for editing the specified crop
    public function edit($id)
    {
        $crop = InventoryValuedCrop::with(['farmer', 'plant'])->findOrFail($id);

        return response()->json([
            'farmer' => $crop->farmer->first_name . ' ' . $crop->farmer->last_name,
            'plant' => $crop->plant->name_of_plants,
            'count' => $crop->count,
        ]);
    }

    // Update the specified crop in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'plant_id' => 'required|exists:plants,id',
            'count' => 'required|integer|min:1',
        ]);

        $crop = InventoryValuedCrop::findOrFail($id);
        $crop->update($request->all());

        return response()->json(['message' => 'Crop updated successfully.']);
    }

    // Remove the specified crop from storage
    public function destroy($id)
    {
        $crop = InventoryValuedCrop::findOrFail($id);
        $crop->delete();

        return response()->json(['message' => 'Crop deleted successfully.']);
    }
}
