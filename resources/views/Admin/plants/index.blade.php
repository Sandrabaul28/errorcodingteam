@extends('layouts.admin.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 text-primary">PLANT MANAGEMENT / <span class="font-weight-bold">ADD CROPS/ PLANTS LISTS</span></h6>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form to create a new affiliation -->
        <form action="{{ route('plants.store') }}" method="POST">
            @csrf
            <div class="form-row mb-3">
                <div class="col">
                    <label for="name_of_plants">Name of Plants <span style="color: red;">*</span></label>
                    <input type="text" name="name_of_plants" class="form-control form-control-sm">
                </div>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>
<hr>

<!-- Display the list of affiliations -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 text-primary">PLANT LISTS</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Table for Name of Association -->
            <div class="col-md-6">
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>Name of Plants/Crops</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plants as $plant)
                                <tr>
                                    <td>{{ $plant->name_of_plants }}</td>
                                </tr>
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
