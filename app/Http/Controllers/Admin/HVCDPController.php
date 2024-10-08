<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Affiliation;
use App\Exports\HVCDPExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class HVCDPController extends Controller
{
    public function index(Request $request)
    {
        // I-validate ang mga input na petsa
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'barangay' => 'nullable|string',
        ]);

        // I-query ang mga farmers
        $farmers = Farmer::with('inventoryValuedCrops.plant')->newQuery();

        // Kung may filter na provided
        if ($request->has('from_date') && $request->has('to_date')) {
            // I-format ang petsa para sa timestamp
            $fromDate = $request->from_date . ' 00:00:00'; // simula ng araw
            $toDate = $request->to_date . ' 23:59:59'; // katapusan ng araw

            $farmers->whereBetween('created_at', [$fromDate, $toDate]);
        }

        // Kung gusto mong i-filter ang farmers batay sa affiliation (barangay)
        if ($request->has('barangay')) {
            $farmers->whereHas('affiliation', function($query) use ($request) {
                $query->where('name_of_barangay', $request->barangay);
            });
        }

        // Kunin ang mga farmers
        $farmers = $farmers->get();

        // Kunin ang lahat ng affiliations
        $affiliations = Affiliation::all();

        // Kunin ang mga unique plants
        $uniquePlants = $farmers->flatMap(function($farmer) {
            return $farmer->inventoryValuedCrops->pluck('plant.name_of_plants');
        })->unique();

        return view('admin.hvcdp.index', compact('affiliations', 'farmers', 'uniquePlants'), [
            'title' => 'HVCDP - Records'
        ]);
    }

    public function create()
    {
        return view('hvcdp.create');
    }

    public function show($id)
    {
        $farmer = Farmer::findOrFail($id);
        return view('admin.hvcdp.show', compact('farmer'));
    }

    public function edit($id)
    {
        $farmer = Farmer::with('inventoryValuedCrops')->findOrFail($id);
        return view('admin.hvcdp.edit', compact('farmer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        // Update the farmer record
        $farmer = Farmer::findOrFail($id);
        $farmer->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        return redirect()->route('hvcdp.index')->with('success', 'Farmer record updated successfully.');
    }

    public function destroy($id)
    {
        $farmer = Farmer::findOrFail($id);
        $farmer->delete();

        return redirect()->route('hvcdp.index')->with('success', 'Farmer record deleted successfully.');
    }

    // Updated print method to include filters
    public function print(Request $request)
    {
        // I-validate ang mga input na petsa
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'barangay' => 'nullable|string',
        ]);

        // I-query ang mga farmers
        $farmers = Farmer::with(['inventoryValuedCrops.plant', 'affiliation'])->newQuery();

        // Kung may filter na provided
        if ($request->has('from_date') && $request->has('to_date')) {
            $fromDate = $request->from_date . ' 00:00:00'; // simula ng araw
            $toDate = $request->to_date . ' 23:59:59'; // katapusan ng araw

            $farmers->whereBetween('created_at', [$fromDate, $toDate]);
        }

        // Kung gusto mong i-filter ang farmers batay sa affiliation (barangay)
        if ($request->has('barangay')) {
            $farmers->whereHas('affiliation', function($query) use ($request) {
                $query->where('name_of_barangay', $request->barangay);
            });
        }

        // Kunin ang mga farmers
        $farmers = $farmers->get();

        // Kunin ang mga unique plants mula sa mga filtered farmers
        $uniquePlants = $farmers->flatMap(function($farmer) {
            return $farmer->inventoryValuedCrops->pluck('plant.name_of_plants');
        })->unique()->values();

        return view('admin.hvcdp.print', compact('farmers', 'uniquePlants'), [
            'title' => 'HVCDP Print'
        ]);
    }

    // Method to export data filtered by Barangay
    public function exportBarangay(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'barangay' => 'nullable|string',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $barangay = $request->input('barangay');

        // Query to filter farmers based on barangay and date range
        $farmers = Farmer::with(['inventoryValuedCrops.plant', 'affiliation'])
            ->when($request->from_date && $request->to_date, function ($query) use ($request) {
                $fromDate = $request->from_date . ' 00:00:00'; // Start of the day
                $toDate = $request->to_date . ' 23:59:59'; // End of the day
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->when($barangay, function ($query) use ($barangay) {
                $query->whereHas('affiliation', function ($q) use ($barangay) {
                    $q->where('name_of_barangay', $barangay);
                });
            })
            ->get();

        // Pass the filtered farmers to the export class
        return Excel::download(new HVCDPExport($barangay, $request->from_date, $request->to_date, $farmers), 'hvcdp_' . ($barangay ?? 'all') . '.xlsx');
    }


}
