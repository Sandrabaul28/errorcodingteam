@extends('layouts.admin.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 text-primary">HVCDP Records</h6>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('hvcdp.count')}}" class="btn btn-primary">Add Record</a>
            <div>
                <a href="{{ route('hvcdp.print', ['from_date' => request('from_date'), 'to_date' => request('to_date'), 'barangay' => request('barangay')]) }}" class="btn btn-primary" target="_blank">Print</a>
                <button class="btn btn-secondary">CSV</button>
                <a href="{{ route('hvcdp.exportExcel', ['barangay' => request('barangay'), 'from_date' => request('from_date'), 'to_date' => request('to_date')]) }}" class="btn btn-success" target="_blank">Export Excel</a>

            </div>
        </div>

        <div class="mb-4">
            <!-- Filter by Date Form -->
            <form action="{{ route('hvcdp.index') }}" method="GET" class="form-inline mb-3">
                <label for="from_date">From: </label>
                <input type="date" name="from_date" id="from_date" class="form-control mx-2">
                <label for="to_date">To: </label>
                <input type="date" name="to_date" id="to_date" class="form-control mx-2">
                <button type="submit" class="btn btn-success mx-2">Filter</button>
                <a href="{{ route('hvcdp.index') }}" class="btn btn-secondary mx-2">Reset</a>
            </form>

            <!-- Filter by Barangay Form -->
            <form action="{{ route('hvcdp.index') }}" method="GET" class="form-inline">
                <label for="barangay">Filter by Barangay: </label>
                <select name="barangay" id="barangay" class="form-control mx-2">
                    <option value="">-- Select Barangay --</option>
                    @foreach($affiliations as $affiliation)
                        <option value="{{ $affiliation->name_of_barangay }}">{{ $affiliation->name_of_barangay }} - {{ $affiliation->name_of_association }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-success mx-2">Filter</button>
                <a href="{{ route('hvcdp.index') }}" class="btn btn-secondary mx-2">Reset</a>
            </form>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Surname</th>
                    <th>Firstname</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($farmers as $farmer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $farmer->last_name }}</td>
                    <td>{{ $farmer->first_name }}</td>
                    <td>
                        <button class="btn btn-info" data-toggle="modal" data-target="#viewModal{{ $farmer->id }}">View</button>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#editModal{{ $farmer->id }}">Edit</button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $farmer->id }}">Delete</button>
                    </td>
                </tr>

                <!-- View Modal -->
                <div class="modal fade" id="viewModal{{ $farmer->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $farmer->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewModalLabel{{ $farmer->id }}">Farmer Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Surname: </strong>{{ $farmer->last_name }}</p>
                                <p><strong>Firstname: </strong>{{ $farmer->first_name }}</p>
                                <p><strong>Plants:</strong></p>
                                <ul>
                                    @foreach($farmer->inventoryValuedCrops as $inventoryValuedCrop)
                                        <li>{{ $inventoryValuedCrop->plant->name_of_plants }} - {{ $inventoryValuedCrop->count }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $farmer->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $farmer->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action="{{ route('hvcdp.update', $farmer->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $farmer->id }}">Edit Farmer</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="last_name">Surname *</label>
                                        <input type="text" class="form-control" name="last_name" value="{{ $farmer->last_name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="first_name">Firstname *</label>
                                        <input type="text" class="form-control" name="first_name" value="{{ $farmer->first_name }}">
                                    </div>
                                    <!-- Plants and quantities -->
                                    <div class="form-group">
                                        <label for="plants">Plants</label>
                                        <ul>
                                            @foreach($farmer->inventoryValuedCrops as $inventoryValuedCrop)
                                            <li>
                                                <label>{{ $inventoryValuedCrop->plant->name_of_plants }}</label>
                                                <input type="number" name="plants[{{ $inventoryValuedCrop->plant_id }}]" value="{{ $inventoryValuedCrop->count }}" class="form-control">
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal{{ $farmer->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $farmer->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel{{ $farmer->id }}">Delete Farmer</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete {{ $farmer->first_name }} {{ $farmer->last_name }}?
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('hvcdp.destroy', $farmer->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
