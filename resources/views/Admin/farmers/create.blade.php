@extends('layouts.admin.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 text-success">ADD NEW FARMER</h6>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.farmers.store') }}" method="POST">
            @csrf

            <!-- Farmer Details Section -->
            <div class="form-row mb-3">
                <div class="col">
                    <label for="first_name">First Name <span style="color: red;">*</span></label>
                    <input type="text" name="first_name" placeholder="First name" class="form-control form-control-sm" required>
                </div>
                <div class="col">
                    <label for="last_name">Last Name <span style="color: red;">*</span></label>
                    <input type="text" name="last_name" placeholder="Last name" class="form-control form-control-sm" required>
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col">
                    <label for="middle_name">Middle Name (Optional)</label>
                    <input type="text" name="middle_name" placeholder="Middle Initial" class="form-control form-control-sm">
                </div>
                <div class="col">
                    <label for="extension">Extension (e.g., Jr, Sr)</label>
                    <input type="text" name="extension" placeholder="jr, sr, etc." class="form-control form-control-sm">
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col">
                    <label for="affiliation_id">Affiliation</label>
                    <select name="affiliation_id" class="form-control form-control-sm">
                        @foreach($affiliations as $affiliation)
                            <option value="{{ $affiliation->id }}">{{ $affiliation->name_of_association ?? $affiliation->name_of_barangay }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <label for="email">Email Address <span style="color: red;">*</span></label>
                    <input type="email" name="email" placeholder="Email address" class="form-control form-control-sm" required>
                </div>
                <div class="col">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="************" class="form-control form-control-sm @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="************" class="form-control form-control-sm" required>
                </div>
                    <!-- Hidden role_id field -->
                <input type="hidden" name="role_id" value="2">
            </div>


            <button type="submit" class="btn btn-danger">Add Farmer and Encoder</button>
            <a href="{{ route('admin.affiliations.index')}}" class="btn btn-warning">Add Affiliation</a>

        </form>
    </div>
</div>

<!-- Farmer List Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 text-success">FARMER LISTS</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Affiliation</th>
                    <th>Added by</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($farmers as $farmer)
                    <tr>
                        <td>{{ $farmer->first_name }}</td>
                        <td>{{ $farmer->last_name }}</td>
                        <td>{{ $farmer->affiliation->name_of_association ?? $farmer->affiliation->name_of_barangay ?? 'N/A' }}</td>
                        <td><span style="font-style: italic;">{{ $farmer->addedBy->role->role_name ?? 'N/A' }}</td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="viewFarmer({{ $farmer }})">View</button>
                            <button class="btn btn-warning btn-sm" onclick="editFarmer({{ $farmer }})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $farmer->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@endsection
