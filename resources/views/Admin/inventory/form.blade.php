@extends('layouts.admin.app')

@section('content')
<form action="{{ route('inventory.store', $farmer_id) }}" method="POST">
    @csrf

    @foreach($farmerPlants as $plant)
        <div class="form-group">
            <label for="plant_{{ $plant->id }}">{{ $plant->name_of_plants }}</label>
            <input type="number" name="plants[{{ $plant->id }}]" id="plant_{{ $plant->id }}" class="form-control" placeholder="Enter number of {{ $plant->name_of_plants }}" required>
        </div>
    @endforeach

    <button type="submit" class="btn btn-primary">Submit</button>
</form>


@endsection
