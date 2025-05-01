<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Historical Trends - AQI</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .controls {
            display: flex;
            gap: 10px;
        }

        select, button {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .content {
            display: flex;
            gap: 20px;
        }

        .chart {
            flex: 3;
            background: #d5edf8;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: #6c757d;
            font-size: 18px;
        }

        .stats {
            flex: 1;
            background: #d5edf8;
            padding: 20px;
            border-radius: 10px;
        }

        .stats h3 {
            margin-top: 0;
        }

        .stats p {
            font-size: 18px;
            margin: 5px 0;
        }

        .stats .blue {
            color: #007bff;
        }

        .stats .red {
            color: #dc3545;
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
            padding-top: 80px;
            background-color: #f0f4f7;
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
        <div class="header">
            <h2>Historical Trends</h2>
            <div class="controls">
                <select id="timeRange">
                    <option value="24h">Last 24 Hours</option>
                    <option value="7d">Last 7 Days</option>
                    <option value="30d">Last 30 Days</option>
                </select>
                <button onclick="exportData()">Export Data</button>
            </div>
        </div>

        <div class="content">
            <div class="chart">
                <canvas id="aqiChart"></canvas>
            </div>
            <div class="stats">
                <h3>Statistics</h3>
                <p>Average AQI: <span class="blue" id="avgAqi">-</span></p>
                <p>Peak AQI: <span class="red" id="peakAqi">-</span></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let chart = null;

            async function fetchHistoricalData(timeRange) {
                try {
                    const response = await fetch(`/api/historical-data/${timeRange}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();
                    updateChart(data);
                    updateStats(data);
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }

            function updateChart(data) {
                const ctx = document.getElementById('aqiChart').getContext('2d');
                
                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'AQI Level',
                            data: data.values,
                            borderColor: '#3498db',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            function updateStats(data) {
                const avgAqi = data.values.reduce((a, b) => a + b, 0) / data.values.length;
                const peakAqi = Math.max(...data.values);
                
                document.getElementById('avgAqi').textContent = avgAqi.toFixed(1);
                document.getElementById('peakAqi').textContent = peakAqi;
            }

            // Handle time range changes
            document.getElementById('timeRange').addEventListener('change', function(e) {
                fetchHistoricalData(e.target.value);
            });

            // Initial data fetch
            fetchHistoricalData('24h');
        });

        async function exportData() {
            try {
                const timeRange = document.getElementById('timeRange').value;
                const response = await fetch(`/api/historical-data/${timeRange}/export`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) throw new Error('Export failed');
                
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `aqi_data_${timeRange}_${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                a.remove();
            } catch (error) {
                console.error('Export error:', error);
                alert('Failed to export data');
            }
        }
    </script>
</body>
</html>
