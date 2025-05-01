<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sensor Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #2d3748;
            margin: 0;
            font-size: 28px;
        }
        
        .add-btn {
            background-color: #3182ce;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .add-btn:hover {
            background-color: #2b6cb0;
        }
        
        .sensor-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .sensor-table th {
            background-color: #f7fafc;
            text-align: left;
            padding: 15px;
            font-weight: 600;
            color: #2d3748;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .sensor-table tr {
            border-bottom: 1px solid #e2e8f0;
        }
        
        .sensor-table tr:last-child {
            border-bottom: none;
        }
        
        .sensor-table td {
            padding: 15px;
            color: #2d3748;
        }
        
        .sensor-row {
            background-color: #b2f5ea;
        }
        
        .status-badge {
            background-color: #10b981;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
        }
        
        .action-btn {
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            margin-right: 5px;
            border: 1px solid #e2e8f0;
        }
        
        .edit-btn {
            background-color: white;
            color: #2d3748;
        }
        
        .deactivate-btn {
            background-color: #f56565;
            color: white;
            border: none;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .modal h2 {
            margin-top: 0;
            color: #2d3748;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #2d3748;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .modal-buttons button {
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .cancel-btn {
            background-color: white;
            border: 1px solid #e2e8f0;
            color: #2d3748;
        }
        
        .submit-btn {
            background-color: #3182ce;
            color: white;
            border: none;
        }
        
        .trash-icon {
            color: #f56565;
            cursor: pointer;
            margin-left: 10px;
            display: none;
        }
        
        .delete-btn {
            color: #94a3b8;
            cursor: pointer;
            margin-left: 8px;
            transition: color 0.2s;
        }
        
        .delete-btn:hover {
            color: #ef4444;
        }
        .activate-btn {
            background-color: #48bb78;
            color: white;
            border: none;
        }

        .activate-btn:hover {
            background-color: #38a169;
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
            display: none;
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
            background-color: #f0f4f7;
        }
        
        @media (max-width: 768px) {
            .sensor-table {
                font-size: 14px;
            }
            
            .action-btn {
                padding: 4px 10px;
                font-size: 12px;
            }
            
            .status-badge {
                color: white;
                padding: 5px 15px;
                border-radius: 20px;
                font-size: 14px;
                display: inline-block;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">Air Quality Monitor</div>
            <ul class="nav-links">
                <li><a href="{{ route('system-overview') }}">System Overview</a></li>
                <li><a href="{{ route('map') }}">Map</a></li>
                <li><a href="{{ route('historical-data') }}">Historical Data</a></li>
                <li><a href="{{ route('sensor-management') }}" class="active">Sensor Management</a></li>
                <li><a href="{{ route('user-management') }}">User Management</a></li>
                <li><a href="{{ route('simulation') }}">Simulation</a></li>
                <li><a href="{{ route('alerts') }}">Alerts</a></li>
                @guest
                    <li><a href="{{ route('login') }}" class="login-btn">Login</a></li>
                @else
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="login-btn">Logout</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h1>Sensor Management</h1>
            <button class="add-btn" id="addSensorBtn">+ Add New Sensor</button>
        </div>

        <div class="notification" id="notification"></div>
        
        <table class="sensor-table" id="sensorTable">
            <thead>
                <tr>
                    <th>Sensor ID</th>
                    <th>Location</th>
                    <th>Last Update</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="sensorTableBody">
            
            </tbody>
        </table>
    </div>
    
    <div id="addSensorModal" class="modal">
        <div class="modal-content">
            <h2>Add New Sensor</h2>
            <div class="form-group">
                <label for="sensorId">Sensor ID:</label>
                <input type="text" id="sensorId" placeholder="Enter sensor ID">
            </div>
            <div class="form-group">
                <label for="latitude">Latitude:</label>
                <input type="number" id="latitude" placeholder="Enter latitude" step="any">
            </div>
            <div class="form-group">
                <label for="longitude">Longitude:</label>
                <input type="number" id="longitude" placeholder="Enter longitude" step="any">
            </div>
            <div class="modal-buttons">
                <button class="cancel-btn" id="cancelAddBtn">Cancel</button>
                <button class="submit-btn" id="confirmAddBtn">Add</button>
            </div>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener('DOMContentLoaded', function() {
            const addSensorBtn = document.getElementById('addSensorBtn');
            const addSensorModal = document.getElementById('addSensorModal');
            const cancelAddBtn = document.getElementById('cancelAddBtn');
            const confirmAddBtn = document.getElementById('confirmAddBtn');
            const sensorIdInput = document.getElementById('sensorId');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');

            // Add New Sensor button click handler
            addSensorBtn.addEventListener('click', function() {
                clearModalInputs();
                document.querySelector('#addSensorModal h2').textContent = 'Add New Sensor';
                document.querySelector('#confirmAddBtn').textContent = 'Add';
                addSensorModal.style.display = 'block';
                editingSensorId = null;
            });

            // Cancel button click handler
            cancelAddBtn.addEventListener('click', function() {
                addSensorModal.style.display = 'none';
                clearModalInputs();
            });

            // Modal outside click handler
            window.addEventListener('click', function(event) {
                if (event.target === addSensorModal) {
                    addSensorModal.style.display = 'none';
                    clearModalInputs();
                }
            });

            // Confirm Add button click handler
            confirmAddBtn.addEventListener('click', function() {
                const sensorId = sensorIdInput.value.trim();
                const latitude = latitudeInput.value.trim();
                const longitude = longitudeInput.value.trim();

                if (sensorId && latitude && longitude) {
                    if (editingSensorId) {
                        // Edit mode
                        fetch(`/sensors/${editingSensorId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                name: sensorId,
                                latitude: parseFloat(latitude),
                                longitude: parseFloat(longitude),
                                status: 1  // Send as number, not string
                            })
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Network response was not ok');
                            return res.json();
                        })
                        .then(sensor => {
                            fetchSensors();
                            showNotification(`Sensor ${sensorId} was updated successfully`);
                            addSensorModal.style.display = 'none';
                            clearModalInputs();
                            editingSensorId = null;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Failed to update sensor', false);
                        });
                    } else {
                        // Add mode
                        fetch('/sensors', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                name: sensorId,
                                latitude: parseFloat(latitude),
                                longitude: parseFloat(longitude),
                                status: 1
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw new Error(err.message || 'Failed to add sensor');
                                });
                            }
                            return response.json();
                        })
                        .then(sensor => {
                            fetchSensors();
                            showNotification(`Sensor ${sensorId} was added successfully`);
                            addSensorModal.style.display = 'none';
                            clearModalInputs();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification(error.message || 'Failed to add sensor', false);
                        });
                    }
                } else {
                    showNotification('Please fill in all fields', false);
                }
            });

            function clearModalInputs() {
                document.getElementById('sensorId').value = '';
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
            }

            function showNotification(message, isSuccess = true) {
                const notification = document.getElementById('notification');
                notification.textContent = message;
                notification.style.backgroundColor = isSuccess ? '#2ecc71' : '#e74c3c';
                notification.style.display = 'block';
                
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 3000);
            }

            let editingSensorId = null;

            function fetchSensors() {
                fetch('/sensors', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin' // Include cookies if using session auth
                })
                .then(res => {
                    if (!res.ok) {
                        return res.text().then(text => {
                            throw new Error(`API Error (${res.status}): ${text}`);
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    if (!Array.isArray(data)) {
                        throw new Error('Expected array of sensors');
                    }
                    const sensorTableBody = document.getElementById('sensorTableBody');
                    sensorTableBody.innerHTML = '';
                    data.forEach(sensor => {
                        const row = document.createElement('tr');
                        row.className = 'sensor-row';
                        const timeStr = new Date(sensor.updated_at).toLocaleString();
                        
                        row.innerHTML = `
                            <td class="sensor-id">${sensor.name}</td>
                            <td class="sensor-location">${sensor.latitude}, ${sensor.longitude}</td>
                            <td class="timestamp" data-time="${sensor.updated_at}">${timeStr}</td>
                            <td>
                                <span class="status-badge" style="background-color: ${sensor.status == 1 ? '#10b981' : '#9ca3af'}">
                                    ${sensor.status == 1 ? 'Active' : 'Inactive'}
                                </span>
                            </td>
                            <td>
                                <button class="action-btn edit-btn">Edit</button>
                                <button class="action-btn ${sensor.status == 1 ? 'deactivate-btn' : 'activate-btn'}">
                                    ${sensor.status == 1 ? 'Deactivate' : 'Activate'}
                                </button>
                                <span class="delete-btn">✕</span>
                            </td>
                        `;
                        
                        sensorTableBody.appendChild(row);

                        const toggleBtn = row.querySelector('.deactivate-btn, .activate-btn');
                        toggleBtn.addEventListener('click', function() {
                            const isActive = sensor.status == 1;
                            const newStatus = isActive ? 0 : 1;

                            fetch(`/sensors/${sensor.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    name: sensor.name,         // Use existing sensor name
                                    latitude: sensor.latitude,
                                    longitude: sensor.longitude,
                                    status: newStatus          // Send the new status
                                })
                            })
                            .then(res => {
                                if (!res.ok) throw new Error('Failed to update sensor status');
                                return res.json();
                            })
                            .then(() => {
                                fetchSensors();  // Refresh the table
                                showNotification(`Sensor ${sensor.name} was ${newStatus === 1 ? 'activated' : 'deactivated'} successfully`);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showNotification('Failed to update sensor status', false);
                            });
                        });

                        const deleteBtn = row.querySelector('.delete-btn');
                        deleteBtn.addEventListener('click', function() {
                            if(confirm('Are you sure you want to delete this sensor?')) {
                                fetch(`/sensors/${sensor.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    }
                                })
                                .then(response => {
                                    if (response.ok) {
                                        row.remove();
                                        showNotification(`Sensor ${sensor.id} was deleted successfully`);
                                    } else {
                                        throw new Error('Failed to delete sensor');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showNotification('Failed to delete sensor', false);
                                });
                            }
                        });

                        const editBtn = row.querySelector('.edit-btn');
                        editBtn.addEventListener('click', function() {
                            editingSensorId = sensor.id;
                            const [currentLatitude, currentLongitude] = row.querySelector('.sensor-location').textContent.split(',').map(s => s.trim());

                            sensorIdInput.value = sensor.name;
                            latitudeInput.value = currentLatitude;
                            longitudeInput.value = currentLongitude;

                            document.querySelector('#addSensorModal h2').textContent = 'Edit Sensor';
                            document.querySelector('#confirmAddBtn').textContent = 'Save Changes';

                            addSensorModal.style.display = 'block';
                        });
                    });
                })
                .catch(error => {
                    console.error('Error fetching sensors:', error);
                    showNotification('Failed to load sensors. Please try again later.', false);
                });
            }

            setInterval(function() {
                const timestamps = document.querySelectorAll('.timestamp');
                timestamps.forEach(function(element) {
                    const timestamp = new Date(parseInt(element.getAttribute('data-time')));
                    element.textContent = getTimeDifference(timestamp);
                });
            }, 60000); 

            function getTimeDifference(timestamp) {
                const now = new Date();
                const diffMs = now - timestamp;
                const diffSecs = Math.floor(diffMs / 1000);
                const diffMins = Math.floor(diffSecs / 60);
                const diffHours = Math.floor(diffMins / 60);
                const diffDays = Math.floor(diffHours / 24);
                
                if (diffMins < 1) {
                    return 'Just now';
                } else if (diffMins === 1) {
                    return '1 min ago';
                } else if (diffMins < 60) {
                    return `${diffMins} mins ago`;
                } else if (diffHours === 1) {
                    return '1 hour ago';
                } else if (diffHours < 24) {
                    return `${diffHours} hours ago`;
                } else if (diffDays === 1) {
                    return '1 day ago';
                } else {
                    return `${diffDays} days ago`;
                }
            }

            // Initial fetch
            fetchSensors();
        });
    </script>  
</body>
</html>