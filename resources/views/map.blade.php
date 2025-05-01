<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Air Quality Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map { height: 100vh; }
        .legend {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background: rgba(255, 255, 255, 0.9);
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            font-family: 'Poppins', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background-color: #2c3e50;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: 60px;
        }

        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 1rem;
        }

        .nav-links li {
            margin: 0;
            white-space: nowrap;
        }

        .nav-links a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
            padding: 0.5rem 0.75rem;
        }

        .logo {
            color: white;
            font-size: 1.25rem;
            font-weight: bold;
            white-space: nowrap;
            margin-right: 1rem;
        }

        .nav-links a:hover {
            color: #3498db;
        }

        .nav-links a.active {
            color: #3498db;
            font-weight: bold;
        }

        .login-btn {
            background-color: #3498db;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            color: white !important;
        }

        .login-btn:hover {
            background-color: #2980b9;
            color: white !important;
        }

        body {
            padding-top: 60px;
        }

        /* Add styles for the popup */
        .sensor-popup {
            min-width: 200px;
            font-family: 'Segoe UI', sans-serif;
            padding: 10px;
        }

        .sensor-popup h4 {
            margin: 0 0 8px 0;
            color: #2d3748;
        }

        .sensor-popup p {
            margin: 4px 0;
            color: #4a5568;
        }

        .aqi-value {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            background: rgba(0,0,0,0.05);
        }

        .aqi-good { 
            color: #10b981; 
            background: rgba(16, 185, 129, 0.1);
        }
        .aqi-moderate { 
            color: #f59e0b; 
            background: rgba(245, 158, 11, 0.1);
        }
        .aqi-unhealthy { 
            color: #ef4444; 
            background: rgba(239, 68, 68, 0.1);
        }

        /* Add marker pulse animation */
        .marker-pulse {
            animation: pulse 1.5s infinite;
            transition: all 0.3s ease;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .aqi-marker {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">Air Quality Monitor</div>
            <ul class="nav-links">
                <li><a href="{{ route('system-overview') }}" class="{{ request()->routeIs('system-overview') ? 'active' : '' }}">System Overview</a></li>
                <li><a href="{{ route('map') }}" class="{{ request()->routeIs('map') ? 'active' : '' }}">Map</a></li>
                <li><a href="{{ route('historical-data') }}" class="{{ request()->routeIs('historical-data') ? 'active' : '' }}">Historical Data</a></li>
                <li><a href="{{ route('sensor-management') }}" class="{{ request()->routeIs('sensor-management') ? 'active' : '' }}">Sensor Management</a></li>
                <li><a href="{{ route('user-management') }}" class="{{ request()->routeIs('user-management') ? 'active' : '' }}">User Management</a></li>
                <li><a href="{{ route('simulation') }}" class="{{ request()->routeIs('simulation') ? 'active' : '' }}">Simulation</a></li>
                <li><a href="{{ route('alerts') }}" class="{{ request()->routeIs('alerts') ? 'active' : '' }}">Alerts</a></li>
                @guest
                    <li><a href="{{ route('login') }}" class="login-btn">Login</a></li>
                @else
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0">
                            @csrf
                            <button type="submit" class="login-btn" style="border:none;cursor:pointer;font-size:0.9rem">Logout</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>

    <div id="map"></div>
    <div class="legend">
        <h3 style="margin: 0 0 12px 0; color: #1e293b;">AQI Legend</h3>
        <div style="display: flex; align-items: center; margin-bottom: 8px;">
            <div style="width: 20px; height: 20px; background: #10b981; border-radius: 50%; margin-right: 8px;"></div>
            <span>Good (0-50)</span>
        </div>
        <div style="display: flex; align-items: center; margin-bottom: 8px;">
            <div style="width: 20px; height: 20px; background: #f59e0b; border-radius: 50%; margin-right: 8px;"></div>
            <span>Moderate (51-100)</span>
        </div>
        <div style="display: flex; align-items: center;">
            <div style="width: 20px; height: 20px; background: #ef4444; border-radius: 50%; margin-right: 8px;"></div>
            <span>Unhealthy (101+)</span>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const map = L.map('map').setView([6.9271, 79.8612], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        function getColor(aqi) {
            if (aqi <= 50) return '#10b981';  // Good
            if (aqi <= 100) return '#f59e0b'; // Moderate
            return '#ef4444';                 // Unhealthy
        }

        function getAQIClass(aqi) {
            if (aqi <= 50) return 'aqi-good';
            if (aqi <= 100) return 'aqi-moderate';
            return 'aqi-unhealthy';
        }

        function createMarkerIcon(aqi) {
            const color = getColor(aqi);
            return L.divIcon({
                className: 'aqi-marker',
                html: `<div class="marker-pulse" style="
                    background: ${color}; 
                    width: 24px; 
                    height: 24px; 
                    border-radius: 50%; 
                    border: 2px solid white; 
                    box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                </div>`
            });
        }

        function loadSensors() {
            fetch('/api/sensors', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Cache-Control': 'no-cache, no-store, must-revalidate'
                }
            })
            .then(response => response.json())
            .then(sensors => {
                // Clear existing markers
                map.eachLayer((layer) => {
                    if (layer instanceof L.Marker) {
                        map.removeLayer(layer);
                    }
                });

                sensors.forEach(sensor => {
                    if (sensor.status) {  // Only show active sensors
                        const aqi = sensor.aqi || 0; // Fallback to 0 if no AQI value
                        const marker = L.marker([sensor.latitude, sensor.longitude], {
                            icon: createMarkerIcon(aqi)
                        }).addTo(map);

                        marker.bindPopup(`
                            <div class="sensor-popup">
                                <h4>${sensor.name}</h4>
                                <div class="aqi-value ${getAQIClass(aqi)}">
                                    AQI: ${aqi}
                                </div>
                                <p>Status: ${sensor.status ? 'Active' : 'Inactive'}</p>
                                <p>Location: ${sensor.latitude.toFixed(4)}, ${sensor.longitude.toFixed(4)}</p>
                                <p>Last Updated: ${sensor.last_updated ? new Date(sensor.last_updated).toLocaleString() : 'No data'}</p>
                            </div>
                        `);
                    }
                });
            })
            .catch(error => console.error('Error loading sensors:', error));
        }

        // Update the refresh interval to match simulation frequency
        const refreshInterval = setInterval(() => {
            if (document.visibilityState === 'visible') {
                loadSensors();
            }
        }, 5000); // Check every 5 seconds

        // Clear interval when page is hidden
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                clearInterval(refreshInterval);
            }
        });

        // Load sensors initially
        loadSensors();
    </script>
</body>
</html>