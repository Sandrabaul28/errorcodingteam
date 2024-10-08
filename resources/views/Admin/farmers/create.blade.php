@extends('layouts.admin.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 text-primary">ADD NEW FARMER</h6>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('farmers.store') }}" method="POST">
            @csrf

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
            </div>

            <button type="submit" class="btn btn-danger">Add Farmer</button>
            <a href="{{ route('affiliations.index')}}" class="btn btn-warning">Add Affiliation</a>

        </form>
    </div>
</div>

<!-- Farmer List Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 text-primary">FARMER LISTS</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Affiliation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($farmers as $farmer)
                    <tr>
                        <td>{{ $farmer->first_name }}</td>
                        <td>{{ $farmer->last_name }}</td>
                        <td>{{ $farmer->affiliation->name_of_association ?? $farmer->affiliation->name_of_barangay ?? 'N/A' }}</td>
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

<!-- View Farmer Modal -->
<div class="modal fade" id="viewFarmerModal" tabindex="-1" role="dialog" aria-labelledby="viewFarmerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewFarmerModalLabel">View Farmer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>First Name:</strong> <span id="viewFirstName"></span></p>
                <p><strong>Last Name:</strong> <span id="viewLastName"></span></p>
                <p><strong>Middle Name:</strong> <span id="viewMiddleName"></span></p>
                <p><strong>Extension:</strong> <span id="viewExtension"></span></p>
                <p><strong>Affiliation:</strong> <span id="viewAffiliation"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- Edit Farmer Modal -->
<div class="modal fade" id="editFarmerModal" tabindex="-1" role="dialog" aria-labelledby="editFarmerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="editFarmerForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editFarmerModalLabel">Edit Farmer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editFirstName">First Name</label>
                        <input type="text" name="first_name" id="editFirstName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editLastName">Last Name</label>
                        <input type="text" name="last_name" id="editLastName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editMiddleName">Middle Name</label>
                        <input type="text" name="middle_name" id="editMiddleName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editExtension">Extension</label>
                        <input type="text" name="extension" id="editExtension" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editAffiliation">Affiliation</label>
                        <input type="text" name="affiliation" id="editAffiliation" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Farmer Modal -->
<div class="modal fade" id="deleteFarmerModal" tabindex="-1" role="dialog" aria-labelledby="deleteFarmerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFarmerModalLabel">Delete Farmer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this farmer?</p>
            </div>
            <div class="modal-footer">
                <form action="" method="POST" id="deleteFarmerForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // View Farmer
    function viewFarmer(farmer) {
        document.getElementById('viewFirstName').textContent = farmer.first_name;
        document.getElementById('viewLastName').textContent = farmer.last_name;
        document.getElementById('viewMiddleName').textContent = farmer.middle_name;
        document.getElementById('viewExtension').textContent = farmer.extension;
        document.getElementById('viewAffiliation').textContent = farmer.affiliation.name_of_association ?? farmer.affiliation.name_of_barangay;
        $('#viewFarmerModal').modal('show');
    }

    // Edit Farmer
    // Edit Farmer
    function editFarmer(farmer) {
        document.getElementById('editFarmerForm').action = `/farmers/${farmer.id}`; // Dynamic URL para sa edit form
        document.getElementById('editFirstName').value = farmer.first_name;
        document.getElementById('editLastName').value = farmer.last_name;
        document.getElementById('editMiddleName').value = farmer.middle_name;
        document.getElementById('editExtension').value = farmer.extension;
        document.getElementById('editAffiliation').value = farmer.affiliation_id; // Use affiliation ID
        $('#editFarmerModal').modal('show'); // Show modal
    }

    // Confirm Delete
    function confirmDelete(farmerId) {
        document.getElementById('deleteFarmerForm').action = `/farmers/${farmerId}`; // Dynamic URL para sa delete form
        $('#deleteFarmerModal').modal('show'); // Show delete confirmation modal
    }


    // Success message fade out after 5 seconds
    setTimeout(function () {
        document.getElementById('success-message')?.classList.add('fade');
    }, 5000);
</script>
@endsection
