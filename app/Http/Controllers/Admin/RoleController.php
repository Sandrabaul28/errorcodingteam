<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Affiliation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    // Constructor to apply auth middleware to all methods
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $users = User::with('role')->get();
        return view('admin.roles.index', compact('users'), [
            'title' => 'Role Management'
        ]);
    }

    public function createUser()
    {
        $roles = Role::all();
        $affiliations = Affiliation::all();
        return view('admin.roles.createUser', compact('roles', 'affiliations'), [
            'title' => 'Create User',
            'users' => User::all()
        ]);
    }


    public function storeUser(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension' => 'nullable|string|max:10',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'affiliation_id' => 'required|exists:affiliations,id',
        ]);
        

        // Create a new user and assign the validated data
        $user = new User();
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->middle_name   = $validated['middle_name'];
        $user->extension = $validated['extension'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']); // Encrypt the password
        $user->role_id = $validated['role_id']; // Assign role ID
        $user->affiliation_id = $validated['affiliation_id']; // Assign affiliation ID
        $user->save(); // Save the user to the database

        // Redirect back with success message
        return redirect()->route('roles.createUser')->with('success', 'User created successfully.');
    }
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $affiliations = Affiliation::all();
        return view('admin.roles.edit-user', compact('user', 'roles', 'affiliations'));
    }

    public function updateUser(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:8',
            'role' => 'required',
            'affiliation_id' => 'required|exists:affiliations,id',
        ]);

        $user = User::findOrFail($id);
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        if ($validated['password']) {
            $user->password = bcrypt($validated['password']);
        }
        $user->role_id = $validated['role'];
        $user->affiliation_id = $validated['affiliation_id'];
        $user->save();

        return redirect()->route('admin.roles.index')->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.roles.index')->with('success', 'User deleted successfully.');
    }
}
