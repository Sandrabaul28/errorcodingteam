<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliation;
use Illuminate\Http\Request;

class AffiliationController extends Controller
{
    public function index()
    {
        // Kumuha ng lahat ng affiliations mula sa database
        $affiliations = Affiliation::all();

        // Ibalik ang view kasama ang affiliations data
        return view('admin.affiliations.index', compact('affiliations'), [
            'title' => 'Create Affiliation'
        ]);
    }

    public function store(Request $request)
    {
        // Validasyon ng request data
        $request->validate([
            'name_of_association' => 'nullable|string|max:255',
            'name_of_barangay' => 'nullable|string|max:255',
        ]);

        // Lumikha ng bagong affiliation
        Affiliation::create([
            'name_of_association' => $request->name_of_association,
            'name_of_barangay' => $request->name_of_barangay,
        ]);

        // Iredirect ang user pabalik sa index page na may success message
        return redirect()->route('admin.affiliations.index')
                         ->with('success', 'Affiliation successfully created.');
    }
}
