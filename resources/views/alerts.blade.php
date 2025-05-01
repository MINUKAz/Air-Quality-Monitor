<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alert Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            padding-top: 80px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        h2, h3 {
            color: #2d3748;
            margin-bottom: 1.5rem;
            font-weight: bold;
        }

        /* Navbar Styles */
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

        /* Form Styling */
        .threshold-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        label {
            font-weight: bold;
            margin-right: 15px;
            display: inline-block;
            width: 120px;
        }

        input[type="number"] {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            width: 100px;
            margin-bottom: 15px;
        }

        button {
            background-color: #3b82f6;
            border: none;
            padding: 8px 16px;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2563eb;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        table th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #475569;
        }

        table tr:hover {
            background-color: #f8fafc;
        }

        /* Status Badges */
        .status-active {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.875rem;
        }

        .status-warning {
            background-color: #ffc107;
            color: #000;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.875rem;
        }

        .status-danger {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.875rem;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 16px;
            background-color: #2ecc71;
            color: white;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
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
        <h2>Alert Management</h2>

        <!-- Set AQI Thresholds -->
        <div class="threshold-form">
            <h3>Set Thresholds</h3>
            <div>
                <label>Moderate AQI:</label>
                <input type="number" id="moderate" value="{{ $thresholds['moderate'] ?? '' }}">
            </div>
            <div>
                <label>Unhealthy AQI:</label>
                <input type="number" id="unhealthy" value="{{ $thresholds['unhealthy'] ?? '' }}">
            </div>
            <div>
                <label>Hazardous AQI:</label>
                <input type="number" id="hazardous" value="{{ $thresholds['hazardous'] ?? '' }}">
            </div>
            <button onclick="setThresholds()">Save Thresholds</button>
        </div>

        <!-- Alerts Table -->
        <h3>Current Alerts</h3>
        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Location</th>
                    <th>AQI Level</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="alertsTable">
                @foreach($alerts ?? [] as $alert)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($alert->timestamp)->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $alert->location }}</td>
                        <td>{{ $alert->aqiLevel }}</td>
                        <td><span class="status-{{ $alert->getStatusClass() }}">{{ $alert->getStatusText() }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Fetch current alerts from the backend
            async function fetchAlerts() {
                try {
                    const response = await fetch('/api/alerts', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const alerts = await response.json();
                    const alertsTable = document.getElementById('alertsTable');
                    alertsTable.innerHTML = '';

                    alerts.forEach(alert => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${new Date(alert.timestamp).toLocaleString()}</td>
                            <td>${alert.location}</td>
                            <td>${alert.aqiLevel}</td>
                            <td><span class="status-${getStatusClass(alert.aqiLevel)}">${getStatusText(alert.aqiLevel)}</span></td>
                        `;
                        alertsTable.appendChild(row);
                    });
                } catch (error) {
                    console.error('Error fetching alerts:', error);
                    showNotification('Failed to load alerts', false);
                }
            }

            // Fetch threshold values from the backend
            async function fetchThresholds() {
                try {
                    const response = await fetch('/api/thresholds', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();
                    document.getElementById('moderate').value = data.moderate;
                    document.getElementById('unhealthy').value = data.unhealthy;
                    document.getElementById('hazardous').value = data.hazardous;
                } catch (error) {
                    console.error('Error fetching thresholds:', error);
                    showNotification('Failed to load thresholds', false);
                }
            }

            // Save threshold values to the backend
            async function setThresholds() {
                const thresholds = {
                    moderate: parseInt(document.getElementById('moderate').value),
                    unhealthy: parseInt(document.getElementById('unhealthy').value),
                    hazardous: parseInt(document.getElementById('hazardous').value)
                };

                try {
                    const response = await fetch('/api/thresholds', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(thresholds)
                    });

                    if (!response.ok) {
                        throw new Error('Failed to update thresholds');
                    }

                    showNotification('Thresholds updated successfully');
                } catch (error) {
                    console.error('Error saving thresholds:', error);
                    showNotification('Failed to update thresholds', false);
                }
            }

            // Helper functions for status display
            function getStatusClass(aqiLevel) {
                const moderate = parseInt(document.getElementById('moderate').value);
                const unhealthy = parseInt(document.getElementById('unhealthy').value);
                
                if (aqiLevel <= moderate) return 'active';
                if (aqiLevel <= unhealthy) return 'warning';
                return 'danger';
            }

            function getStatusText(aqiLevel) {
                const moderate = parseInt(document.getElementById('moderate').value);
                const unhealthy = parseInt(document.getElementById('unhealthy').value);
                
                if (aqiLevel <= moderate) return 'Good';
                if (aqiLevel <= unhealthy) return 'Moderate';
                return 'Hazardous';
            }

            // Notification handler
            function showNotification(message, isSuccess = true) {
                const notification = document.createElement('div');
                notification.className = 'notification';
                notification.textContent = message;
                notification.style.backgroundColor = isSuccess ? '#2ecc71' : '#e74c3c';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }

            // Initialize page with Laravel-specific changes
            function initializePage() {
                // Load initial data
                fetchThresholds();
                fetchAlerts();

                // Set up auto-refresh for alerts every 30 seconds
                setInterval(fetchAlerts, 30000);
            }

            // Start the application
            initializePage();
        });
    </script>
</body>
</html>