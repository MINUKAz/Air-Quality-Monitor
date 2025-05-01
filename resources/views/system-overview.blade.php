<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Air Quality Monitoring System Dashboard</title>
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
            padding-top: 60px; 
        }
        
        h1 {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #2c3e50;
            padding-left: 10px;
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
            max-width: 1400px; /* Increased max-width */
            margin: 0 auto;
            padding: 0 1rem;
        }

        .logo {
            color: white;
            font-size: 1.25rem;
            font-weight: bold;
            white-space: nowrap;
            margin-right: 1rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 1rem; /* Reduced gap */
        }

        .nav-links li {
            margin: 0;
            white-space: nowrap;
        }

        .nav-links a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 0.9rem; /* Slightly smaller font */
            transition: color 0.3s ease;
            padding: 0.5rem 0.75rem; /* Added padding */
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

        .content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .dashboard-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 150px;
        }
        
        .dashboard-card h2 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .dashboard-card p {
            color: #7f8c8d;
            font-size: 1rem;
        }
        
        .active-sensors h2 {
            color: #3498db;
        }
        
        .system-health h2 {
            color: #2ecc71;
        }
        
        .active-alerts h2 {
            color: #e74c3c;
        }
        
        .activity-card, .sensor-status-card {
            min-height: 250px;
            align-items: flex-start;
        }
        
        .card-title {
            font-size: 1.2rem;
            color: #2c3e50;
            margin-bottom: 20px;
            align-self: flex-start;
            font-weight: bold;
        }
        
        .activity-chart {
            width: 100%;
            height: 180px;
            background-color: #b2e0e0;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #7f8c8d;
        }
        
        .sensor-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .sensor-badge {
            padding: 8px 15px;
            border-radius: 20px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .sensor-badge:hover {
            transform: translateY(-3px);
        }
        
        .sensor-badge.active {
            background-color: #3498db;
        }
        
        .sensor-badge.inactive {
            background-color: #7f8c8d;
        }
        
        .sensor-badge.warning {
            background-color: #f39c12;
        }
        
        .sensor-badge.good {
            background-color: #2ecc71;
        }

        /* Add to the style section of each HTML file */
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
            max-width: 1400px; /* Increased max-width */
            margin: 0 auto;
            padding: 0 1rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 1rem; /* Reduced gap */
        }

        .nav-links li {
            margin: 0;
            white-space: nowrap;
        }

        .nav-links a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 0.9rem; /* Slightly smaller font */
            transition: color 0.3s ease;
            padding: 0.5rem 0.75rem; /* Added padding */
        }

        /* Update logo style */
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

        /* Add this to ensure content doesn't hide behind navbar */
        body {
            padding-top: 80px;
        }
        
        #activity-canvas {
            width: 100%;
            height: 100%;
        }

        #navbar-placeholder {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
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
    <h1>System Overview</h1>
    
    <div class="dashboard-container">
        <div class="dashboard-card active-sensors">
            <h2 id="active-sensors-count">{{ $systemData['activeSensors'] }}</h2>
            <p>Active Sensors</p>
        </div>
        
        <div class="dashboard-card system-health">
            <h2 id="system-health-percentage">{{ $systemData['systemHealth'] }}%</h2>
            <p>System Health</p>
        </div>
        
        <div class="dashboard-card active-alerts">
            <h2 id="active-alerts-count">{{ $systemData['activeAlerts'] }}</h2>
            <p>Active Alerts</p>
        </div>
    </div>
    
    <div class="dashboard-container">
        <div class="dashboard-card activity-card">
            <div class="card-title">Recent Activity</div>
            <div class="activity-chart">
                <canvas id="activity-canvas"></canvas>
            </div>
        </div>
        
        <div class="dashboard-card sensor-status-card">
            <div class="card-title">Sensor Status</div>
            <div class="sensor-badges" id="sensor-badges-container">
                @foreach($systemData['sensors'] as $sensor)
                    <div class="sensor-badge {{ $sensor['healthLevel'] }}" 
                         data-sensor-id="{{ $sensor['id'] }}"
                         data-status="{{ $sensor['status'] }}">
                        {{ $sensor['id'] }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
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

            // Initialize the activity chart with data from backend
            const activityData = @json($systemData['recentActivity']);
            
            const activityChart = new Chart(document.getElementById('activity-canvas'), {
                type: 'line',
                data: {
                    labels: activityData.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    }),
                    datasets: [{
                        label: 'System Activity',
                        data: activityData.map(item => item.value),
                        fill: true,
                        backgroundColor: 'rgba(52, 152, 219, 0.2)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            padding: 10,
                            cornerRadius: 4,
                            caretSize: 6,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Real-time updates (if needed)
            function updateDashboard() {
                fetch('/api/dashboard-stats')
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('active-sensors-count').textContent = data.activeSensors;
                        document.getElementById('system-health-percentage').textContent = data.systemHealth + '%';
                        document.getElementById('active-alerts-count').textContent = data.activeAlerts;
                    });
            }

            // Update every 30 seconds
            setInterval(updateDashboard, 30000);
        });

        // Simulate real-time updates
        function simulateRealTimeUpdates() {
            // Update system health percentage
            setInterval(() => {
                const healthValue = Math.floor(Math.random() * 5) + 95; // Random between 95-99
                document.getElementById('system-health-percentage').textContent = healthValue + '%';
            }, 20000);

            // Update active alerts
            setInterval(() => {
                const alertCount = Math.floor(Math.random() * 5); // Random between 0-4
                document.getElementById('active-alerts-count').textContent = alertCount;
            }, 12000);

            // Update activity chart with new data
            setInterval(() => {
                const lastDate = new Date(systemData.recentActivity[systemData.recentActivity.length - 1].date);
                lastDate.setDate(lastDate.getDate() + 1);
                
                const newDateStr = lastDate.toISOString().split('T')[0];
                const newValue = Math.floor(Math.random() * 50) + 40; // Random between 40-89
                
                // Remove first data point and add new one
                systemData.recentActivity.shift();
                systemData.recentActivity.push({ date: newDateStr, value: newValue });
                
                // Update chart data
                activityChart.data.labels = systemData.recentActivity.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                });
                activityChart.data.datasets[0].data = systemData.recentActivity.map(item => item.value);
                
                // Update chart
                activityChart.update();
            }, 30000);
        }

        // Start real-time updates simulation
        simulateRealTimeUpdates();
    </script>
</body>
</html>