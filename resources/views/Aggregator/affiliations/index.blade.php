@extends('layouts.aggregator.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 text-success">ROLES MANAGEMENT / <span class="font-weight-bold">ADD AFFILIATION / AFFILIATION LISTS</span></h6>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form to create a new affiliation -->
        <form action="{{ route('affiliations.store') }}" method="POST">
            @csrf
            <div class="form-row mb-3">
                <div class="col">
                    <label for="name_of_association">Name of Association <span style="color: red;">*</span></label>
                    <input type="text" name="name_of_association" placeholder="Association" class="form-control form-control-sm">
                </div>
                <div class="col">
                    <label for="name_of_barangay">Name of Barangay <span style="color: red;">*</span></label>
                    <input type="text" name="name_of_barangay" placeholder="Barangay" class="form-control form-control-sm">
                </div>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('farmers.create') }}" class="btn btn-warning">Add Farmer</a>
        </form>
    </div>
</div>
<hr>

<!-- Display the list of affiliations -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 text-success">AFFILIATION LISTS</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Table for Name of Association -->
            <div class="col-md-6">
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>Name of Associations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($affiliations as $affiliation)
                            @if ($affiliation->name_of_association !== null)
                                <tr>
                                    <td>{{ $affiliation->name_of_association }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Table for Name of Barangay -->
            <div class="col-md-6">
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>Name of Barangays</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($affiliations as $affiliation)
                            @if ($affiliation->name_of_barangay !== null)
                                <tr>
                                    <td>{{ $affiliation->name_of_barangay }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript to handle the fade-out effect -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            // Set initial opacity and transition
            successMessage.style.opacity = 1;
            successMessage.style.transition = 'opacity 0.6s ease-out';
            
            // Set timeout to start fade-out
            setTimeout(function() {
                successMessage.style.opacity = 0;

                // Remove the element after fade-out
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 600); // Match this delay to the CSS transition duration
            }, 5000); // 5 seconds delay before fade-out starts
        }
    });
</script>
@endsection
