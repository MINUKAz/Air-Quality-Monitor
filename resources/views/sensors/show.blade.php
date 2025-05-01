@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Sensor Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $sensor->name }}</h5>
                <p class="card-text">
                    <strong>Location:</strong> 
                    ({{ $sensor->latitude }}, {{ $sensor->longitude }})
                </p>
                <p class="card-text">
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $sensor->status === 'active' ? 'success' : 'danger' }}">
                        {{ ucfirst($sensor->status) }}
                    </span>
                </p>
                <a href="{{ route('sensors.edit', $sensor->id) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('sensors.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
        
        <!-- Optional: Embed a mini-map -->
        <div class="mt-3">
            <div id="map" style="height: 250px;"></div>
        </div>
    </div>

    <!-- Mini-map script -->
    <script>
        const map = L.map('map').setView([{{ $sensor->latitude }}, {{ $sensor->longitude }}], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.marker([{{ $sensor->latitude }}, {{ $sensor->longitude }}])
            .addTo(map)
            .bindPopup('{{ $sensor->name }}');
    </script>
@endsection