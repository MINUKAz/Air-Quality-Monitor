<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Simulation Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f4f7;
            color: #333;
            padding: 20px;
            margin: 0;
            padding-top: 100px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: #2d3748;
            margin: 0;
            font-size: 28px;
            margin-bottom: 2rem;
        }

        .simulation-panel {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .controls-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .control-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #475569;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1rem;
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .inactive {
            background: #64748b;
        }

        #dataPreview {
            margin-top: 2rem;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Replace the existing navbar styles with these exact styles */
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

        /* Update body padding to match */
        body {
            padding-top: 100px;
            background-color: #f0f4f7;
        }

        /* Add these styles to the existing <style> section in simulation-management.blade.php */
        .text-success {
            color: #10b981;
            font-size: 0.8em;
            margin-left: 4px;
        }

        .text-muted {
            color: #64748b;
            font-size: 0.8em;
            margin-left: 4px;
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

    <div class="container">
        <h1>Data Simulation Management</h1>
        
        <div class="simulation-panel">
            <div class="controls-grid">
                <div>
                    <h2>Simulation Parameters</h2>
                    <div class="control-group">
                        <label for="frequency">Update Frequency</label>
                        <select id="frequency">
                            <option {{ $settings->frequency == 10 ? 'selected' : '' }} value="10">10 seconds</option>
                            <option {{ $settings->frequency == 30 ? 'selected' : '' }} value="30">30 seconds</option>
                            <option {{ $settings->frequency == 60 ? 'selected' : '' }} value="60">1 minute</option>
                            <option {{ $settings->frequency == 300 ? 'selected' : '' }} value="300">5 minutes</option>
                        </select>
                    </div>
                    
                    <div class="control-group">
                        <label for="baseline">Baseline AQI</label>
                        <input type="number" id="baseline" value="{{ $settings->baseline }}" min="0" max="500">
                    </div>
                    
                    <div class="control-group">
                        <label for="variation">AQI Variation</label>
                        <input type="range" id="variation" min="0" max="50" value="{{ $settings->variation }}">
                        <span id="variationValue">±{{ $settings->variation }}</span>
                    </div>
                </div>

                <div>
                    <h2>Simulation Controls</h2>
                    <div class="status-indicator">
                        <div class="status-dot {{ $settings->is_running ? 'active' : 'inactive' }}"></div>
                        <span>Simulation Status: {{ $settings->is_running ? 'Running' : 'Stopped' }}</span>
                    </div>
                    <button class="btn-primary" id="btnStart" {{ $settings->is_running ? 'disabled' : '' }}>Start Simulation</button>
                    <button class="btn-danger" id="btnStop" {{ !$settings->is_running ? 'disabled' : '' }}>Stop Simulation</button>
                </div>
            </div>

            <div id="dataPreview">
                <h3>Live Data Preview</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Sensor ID</th>
                            <th>AQI Value</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="dataBody">
                        @foreach($sensors as $sensor)
                            @php
                                $aqiData = $simulationService->getOrGenerateAQIData($sensor->id);
                                $settings = app(\App\Models\SimulationSetting::class)->first();
                            @endphp
                            <tr>
                                <td>
                                    {{ $settings->is_running ? now()->format('H:i:s') : \Carbon\Carbon::parse($aqiData['timestamp'])->format('H:i:s') }}
                                    <small class="{{ $settings->is_running ? 'text-success' : 'text-muted' }}">
                                        ({{ $settings->is_running ? 'Live' : 'Last updated' }})
                                    </small>
                                </td>
                                <td>{{ $sensor->name }}</td>
                                <td>{{ $aqiData['aqi'] ?? $sensor->last_aqi }}</td>
                                <td>{{ $sensor->status ? 'Active' : 'Inactive' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        document.addEventListener('DOMContentLoaded', function() {
            // Get current page filename
            const currentPage = window.location.pathname.split('/').pop();
            
            // Find and set active link
            const navLinks = document.querySelectorAll('.nav-links a');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
        let simulationInterval = null;
        let isSimulating = false;
        
        // DOM Elements
        const btnStart = document.getElementById('btnStart');
        const btnStop = document.getElementById('btnStop');
        const variationInput = document.getElementById('variation');
        const variationValue = document.getElementById('variationValue');
        const statusDot = document.querySelector('.status-dot');
        const statusText = document.querySelector('.status-indicator span');
        const dataBody = document.getElementById('dataBody');

        // Event Listeners
        variationInput.addEventListener('input', updateVariation);
        btnStart.addEventListener('click', startSimulation);
        btnStop.addEventListener('click', stopSimulation);

        function updateVariation() {
            variationValue.textContent = `±${variationInput.value}`;
        }

        function generateAQI(baseline, variation) {
            const min = baseline - parseInt(variation);
            const max = baseline + parseInt(variation);
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function getAQIStatus(aqi) {
            if(aqi <= 50) return 'Good';
            if(aqi <= 100) return 'Moderate';
            return 'Unhealthy';
        }

        function addDataRow(aqi) {
            const now = new Date();
            const newRow = document.createElement('tr');
            
            newRow.innerHTML = `
                <td>${now.toLocaleTimeString()}</td>
                <td>COL-${Math.floor(100 + Math.random() * 900)}</td>
                <td>${aqi}</td>
                <td>${getAQIStatus(aqi)}</td>
            `;

            dataBody.insertBefore(newRow, dataBody.firstChild);
            
            // Keep only last 5 entries
            if(dataBody.children.length > 5) {
                dataBody.removeChild(dataBody.lastChild);
            }
        }

        async function startSimulation() {
            if(isSimulating) return;
            
            try {
                // Save configuration first
                await saveConfig();
                
                const response = await fetch('/simulation/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                isSimulating = true;
                
                // Update UI elements
                btnStart.disabled = true;
                btnStop.disabled = false;
                statusDot.classList.replace('inactive', 'active');
                statusText.textContent = 'Simulation Status: Running';
                
                // Start data generation with selected frequency
                const frequency = parseInt(document.getElementById('frequency').value) * 1000;
                simulationInterval = setInterval(updateSimulationData, frequency);
                updateSimulationData(); // Initial update
            } catch (error) {
                console.error('Error starting simulation:', error);
            }
        }

        async function stopSimulation() {
            if (!isSimulating) return;
            
            try {
                await fetch('/simulation/stop', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                clearInterval(simulationInterval);
                simulationInterval = null;
                isSimulating = false;
                
                // Update UI
                btnStart.disabled = false;
                btnStop.disabled = true;
                statusDot.classList.replace('active', 'inactive');
                statusText.textContent = 'Simulation Status: Stopped';
            } catch (error) {
                console.error('Error stopping simulation:', error);
            }
        }

        async function loadSettings() {
            const res = await fetch('/simulation/config');
            const cfg = await res.json();
            document.getElementById('frequency').value = cfg.frequency;
            document.getElementById('baseline').value = cfg.baseline;
            variationInput.value = cfg.variation;
            updateVariation();
            updateSimulationStatus(cfg.is_running);
        }

        async function saveConfig() {
            try {
                const payload = {
                    frequency: parseInt(document.getElementById('frequency').value),
                    baseline: parseInt(document.getElementById('baseline').value),
                    variation: parseInt(variationInput.value),
                };
                
                const res = await fetch('/simulation/config', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) throw new Error('Failed to save configuration');
                return true;
            } catch (error) {
                console.error('Error saving configuration:', error);
                alert('Failed to save configuration');
                return false;
            }
        }

        // Update event listeners
        btnStart.addEventListener('click', async () => {
            await saveConfig();
            await fetch('/simulation/start', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            updateSimulationStatus(true);
            startLocalPreview();
        });

        btnStop.addEventListener('click', async () => {
            await fetch('/simulation/stop', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            updateSimulationStatus(false);
            stopLocalPreview();
        });

        // Add function to update System Overview
        async function updateSystemOverview() {
            const response = await fetch('/api/dashboard-stats');
            const data = await response.json();
            
            if (window.parent.document.getElementById('active-sensors-count')) {
                window.parent.document.getElementById('active-sensors-count').textContent = data.activeSensors;
                window.parent.document.getElementById('system-health-percentage').textContent = data.systemHealth + '%';
                window.parent.document.getElementById('active-alerts-count').textContent = data.activeAlerts;
            }
        }

        // Load settings on page load
        loadSettings();

        // Initial load
        updateVariation();

        // Replace the existing updateSimulationData function with this:
        async function updateSimulationData() {
            if (!document.hidden && isSimulating) {
                try {
                    // Get current simulation parameters
                    const baseline = parseInt(document.getElementById('baseline').value);
                    const variation = parseInt(document.getElementById('variation').value);

                    // Update AQI values for all sensors
                    await fetch('/simulation/update-aqi', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ baseline, variation })
                    });

                    // Fetch updated sensor data
                    const response = await fetch('/api/sensors', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Cache-Control': 'no-cache, no-store, must-revalidate'
                        }
                    });
                    
                    const sensors = await response.json();
                    const dataBody = document.getElementById('dataBody');
                    dataBody.innerHTML = '';
                    
                    sensors.forEach(sensor => {
                        const timestamp = new Date().toLocaleTimeString();
                        
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>
                                ${timestamp}
                                <small class="text-success">
                                    (Live)
                                </small>
                            </td>
                            <td>${sensor.name}</td>
                            <td>${sensor.last_aqi || 0}</td>
                            <td>${sensor.status ? 'Active' : 'Inactive'}</td>
                        `;
                        dataBody.appendChild(row);
                    });
                } catch (error) {
                    console.error('Error updating simulation data:', error);
                }
            }
        }

        // Replace the existing startLocalPreview function with this:
        async function startLocalPreview() {
            const frequency = parseInt(document.getElementById('frequency').value) * 1000; // Convert to milliseconds
            if (simulationInterval) {
                clearInterval(simulationInterval);
            }
            simulationInterval = setInterval(updateSimulationData, frequency);
            updateSimulationData(); // Initial update
            isSimulating = true;
        }

        function stopLocalPreview() {
            if (simulationInterval) {
                clearInterval(simulationInterval);
                simulationInterval = null;
            }
            isSimulating = false;
        }

        // Add visibility change handler
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden && simulationInterval) {
                updateSimulationData(); // Update when page becomes visible
            }
        });

        function updateSimulationStatus(running) {
            isSimulating = running;
            btnStart.disabled = running;
            btnStop.disabled = !running;
            statusDot.classList.toggle('active', running);
            statusDot.classList.toggle('inactive', !running);
            statusText.textContent = `Simulation Status: ${running ? 'Running' : 'Stopped'}`;
            
            if (running) {
                startLocalPreview();
            } else {
                stopLocalPreview();
            }
        }
    </script>
</body>
</html>