<!-- Example for create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add New Sensor</h1>
        <form action="{{ route('sensors.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Sensor Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="address">Search Location</label>
                <input type="text" id="address" class="form-control" placeholder="Enter Colombo address">
                <div id="map" style="height: 300px; margin-top: 10px;"></div>
            </div>
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <button type="submit" class="btn btn-primary">Save Sensor</button>
        </form>
    </div>

    <!-- Leaflet.js Map Integration -->
    <script>
        const map = L.map('map').setView([6.9271, 79.8612], 13); // Colombo coordinates
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        let marker;

        // Search address and place marker
        const addressInput = document.getElementById('address');
        addressInput.addEventListener('change', async function() {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${this.value}`);
            const data = await response.json();
            if (data.length > 0) {
                const { lat, lon } = data[0];
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lon;
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lon]).addTo(map);
                map.setView([lat, lon], 15);
            }
        });
    </script>
@endsection