@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 text-success"><span class="font-weight-bold">HIGH VALUED CROPS DEVELOPMENT PROGRAM</span></h6>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.hvcdp.store') }}" method="POST" id="crop-form">
            @csrf
            <div class="form-row">
                <!-- Farmer input -->
                <div class="form-group col-md-4">
                    <label for="farmer_name">Name of Farmer <span style="color: red;">*</span></label>
                    <input list="farmers" id="farmer_name" name="farmer_name" class="form-control" required>
                    <datalist id="farmers">
                        @foreach($farmers as $farmer)
                            <option value="{{ $farmer->first_name }} {{ $farmer->last_name }} {{ $farmer->affiliation_id }}" data-id="{{ $farmer->id }}"></option>
                        @endforeach
                    </datalist>
                    <!-- Hidden field to store the farmer_id -->
                    <input type="hidden" name="farmer_id" id="farmer_id">
                </div>

                <!-- Plant input -->
                <div class="form-group col-md-4">
                    <label for="plant_name">Name of Plant <span style="color: red;">*</span></label>
                    <input list="plants" id="plant_name" name="plant_name" class="form-control" required>
                    <datalist id="plants">
                        @foreach($plants as $plant)
                            <option value="{{ $plant->name_of_plants }}" data-id="{{ $plant->id }}"></option>
                        @endforeach
                    </datalist>
                    <!-- Hidden field to store the plant_id -->
                    <input type="hidden" name="plant_id" id="plant_id">
                </div>

                <!-- Count input -->
                <div class="form-group col-md-4">
                    <label for="count">Count <span style="color: red;">*</span></label>
                    <input type="number" name="count" class="form-control" required>
                </div>
            </div>

            <!-- Submit and Add Farmer buttons -->
            <button type="submit" class="btn btn-success">Add</button>
            <a href="{{ route('admin.farmers.create') }}" class="btn btn-warning">Add Farmer</a>
        </form>


<script>
    // Get the farmer input and hidden field
    const farmerInput = document.getElementById('farmer_name');
    const farmerIdField = document.getElementById('farmer_id');
    
    // Get the plant input and hidden field
    const plantInput = document.getElementById('plant_name');
    const plantIdField = document.getElementById('plant_id');
    
    // Event listener for when farmer is selected
    farmerInput.addEventListener('input', function() {
        const selectedFarmer = Array.from(document.querySelectorAll('#farmers option')).find(option => option.value === farmerInput.value);
        if (selectedFarmer) {
            farmerIdField.value = selectedFarmer.getAttribute('data-id');
        } else {
            farmerIdField.value = ''; // Clear if no match
        }
    });
    
    // Event listener for when plant is selected
    plantInput.addEventListener('input', function() {
        const selectedPlant = Array.from(document.querySelectorAll('#plants option')).find(option => option.value === plantInput.value);
        if (selectedPlant) {
            plantIdField.value = selectedPlant.getAttribute('data-id');
        } else {
            plantIdField.value = ''; // Clear if no match
        }
    });
</script>

<hr>
<div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 text-success"><span class="font-weight-bold">Recorded Inventory</span></h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="inventory-table">
                <thead>
                    <tr>
                        <th>Name of Farmer</th>
                        <th>Affiliation</th>
                        <th>Plants (Name and Count)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventoryCrops as $crop)
                    <tr data-id="{{ $crop->farmer_id }}">
                        <td>{{ $crop->first_name }} {{ $crop->last_name }}</td>
                        <td>
                            @if($crop->name_of_association)
                                {{ $crop->name_of_association }} {{ $crop->name_of_barangay }}
                            @else
                                {{ $crop->name_of_barangay }}
                            @endif
                        </td>
                        <td>{{ $crop->plants_counts }}</td>
                        <td>
                            <button class="btn btn-primary btn-edit" data-id="{{ $crop->farmer_id }}">Edit</button>
                            <button class="btn btn-danger btn-delete" data-id="{{ $crop->farmer_id }}">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Crop Count</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-crop-form">
                    @csrf
                    <input type="hidden" id="edit-crop-id">
                    <div class="form-group">
                        <label for="edit_farmer_id">Name of Farmer <span style="color: red;">*</span></label>
                        <input list="edit_farmers" id="edit_farmer_id" class="form-control" required>
                        <datalist id="edit_farmers">
                            @foreach($farmers as $farmer)
                                <option value="{{ $farmer->first_name }} {{ $farmer->last_name }} ({{ $farmer->affiliation->name_of_association ?? 'N/A' }}, {{ $farmer->affiliation->name_of_barangay ?? 'N/A' }})" data-id="{{ $farmer->id }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label for="edit_plant_id">Name of Plant <span style="color: red;">*</span></label>
                        <input list="edit_plants" id="edit_plant_id" class="form-control" required>
                        <datalist id="edit_plants">
                            @foreach($plants as $plant)
                                <option value="{{ $plant->name_of_plants }}" data-id="{{ $plant->id }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label for="edit_count">Count <span style="color: red;">*</span></label>
                        <input type="number" id="edit_count" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/https://code.jquery.com/jquery-3.6.0.min.js')}}"></script>
<script>
$(document).ready(function() {
    // Populate hidden farmer_id when selecting from the datalist
    $('#farmer_name').on('input', function() {
        var selectedOption = $('#farmers option').filter(function() {
            return this.value === $('#farmer_name').val();
        }).data('id');
        
        if (selectedOption) {
            $('#farmer_id').val(selectedOption); // Set farmer_id
        } else {
            $('#farmer_id').val(''); // Clear the farmer_id if no match is found
        }
    });

    // Populate hidden plant_id when selecting from the datalist
    $('#plant_name').on('input', function() {
        var selectedOption = $('#plants option').filter(function() {
            return this.value === $('#plant_name').val();
        }).data('id');

        if (selectedOption) {
            $('#plant_id').val(selectedOption); // Set plant_id
        } else {
            $('#plant_id').val(''); // Clear the plant_id if no match is found
        }
    });

    // Form submit validation check
    $('#crop-form').submit(function(e) {
        if ($('#farmer_id').val() === '' || $('#plant_id').val() === '') {
            e.preventDefault(); // Prevent form submission
            alert('Please select a valid Farmer and Plant from the list.');
        }
    });

    // Edit functionality
    $('.btn-edit').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ route("admin.hvcdp.edit", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(data) {
                // Populate the modal fields with the data
                $('#editModal #edit_farmer_id').val(data.farmer);
                $('#editModal #edit_plant_id').val(data.plant);
                $('#editModal #edit_count').val(data.count);
                $('#editModal').data('id', id).modal('show');
            },
            error: function(xhr) {
                alert('Error retrieving data. Please try again.');
            }
        });
    });

    // Update functionality
    $('#edit-crop-form').submit(function(e) {
        e.preventDefault(); // Prevent the default form submission
        var id = $('#editModal').data('id');
        var formData = {
            farmer_id: $('#edit_farmer_id').val(),
            plant_id: $('#edit_plant_id').val(),
            count: $('#edit_count').val(),
            _method: 'PUT', // Specify that we're doing a PUT request
            _token: '{{ csrf_token() }}' // CSRF token for security
        };
        $.ajax({
            url: '{{ route("admin.hvcdp.update", ":id") }}'.replace(':id', id),
            method: 'POST',
            data: formData,
            success: function(data) {
                $('#editModal').modal('hide');
                location.reload(); // Reload the page after a successful update
            },
            error: function(xhr) {
                alert('Error updating data. Please try again.');
            }
        });
    });

    // Success message fade out
    setTimeout(function() {
        $('#success-message').fadeOut('slow');
    }, 5000);
});

</script>
@endsection
