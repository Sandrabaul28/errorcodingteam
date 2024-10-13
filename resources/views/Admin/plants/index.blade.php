@extends('layouts.admin.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 text-success">PLANT MANAGEMENT / <span class="font-weight-bold">ADD CROPS/ PLANTS LISTS</span></h6>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.plants.store') }}" method="POST">
    @csrf
        <div class="form-row mb-3">
            <div class="col-md-6">
                <label for="name_of_plants">Name of Plants <span style="color: red;">*</span></label>
                <input type="text" name="name_of_plants" placeholder="chayote, carrot, chili, etc." class="form-control form-control-sm" required>
            </div>
            <div class="col-md-6">
                <label for="variety_name">Variety Name <span style="color: red;">*</span></label>
                <input type="text" name="variety_name" placeholder="varieties" class="form-control form-control-sm" required>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
    </form>

    </div>
</div>

<!-- Display the list of plants and varieties -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 text-success">PLANT LISTS</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>Name of Plants</th>
                            <th>Variety Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plants as $plant)
                            <tr>
                                <td>{{ $plant->name_of_plants }}</td>
                                <td>
                                    @foreach($plant->varieties as $variety)
                                        <span style="font-style: italic;">{{ $variety->variety_name }}</span><br>
                                        <!-- Or use: <em>{{ $variety->variety_name }}</em> -->
                                    @endforeach
                                </td>
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
            successMessage.style.opacity = 1;
            successMessage.style.transition = 'opacity 0.6s ease-out';
            
            setTimeout(function() {
                successMessage.style.opacity = 0;
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 600);
            }, 5000);
        }
    });
</script>
@endsection
