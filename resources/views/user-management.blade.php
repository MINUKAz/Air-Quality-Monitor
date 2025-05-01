<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            background-color: #f0f4f7;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            height: calc(100vh - 40px);
            display: flex;
            flex-direction: column;
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

        .add-button {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 16px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background-color 0.3s;
        }

        .add-button:hover {
            background-color: #2980b9;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .table-header {
            background-color: white;
            padding: 16px;
            display: grid;
            grid-template-columns: 0.8fr 1.2fr 1.5fr 1fr 1.5fr;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 600;
            color: #333;
        }

        .table-body {
            flex: 1;
            overflow: auto;
        }

        .table-row {
            display: grid;
            grid-template-columns: 0.8fr 1.2fr 1.5fr 1fr 1.5fr;
            padding: 16px;
            border-bottom: 1px solid #e0e0e0;
            align-items: center;
        }

        .table-row:nth-child(odd) {
            background-color: #f0f9fc;
        }

        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            display: inline-block;
            text-align: center;
        }

        .status-active {
            background-color: #2ecc71;
            color: white;
        }

        .status-inactive {
            background-color: #95a5a6;
            color: white;
        }

        .status-locked {
            background-color: #e74c3c;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .btn i {
            font-size: 14px;
        }

        .edit-button {
            background-color: white;
            color: #2d3748;
            border: 1px solid #e2e8f0;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .edit-button:hover {
            background-color: #f8f9fa;
        }

        .deactivate-button {
            background-color: #f56565;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .deactivate-button:hover {
            background-color: #c0392b;
        }

        .activate-button {
            background-color: #48bb78;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .activate-button:hover {
            background-color: #38a169;
        }

        .unlock-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .unlock-button:hover {
            background-color: #2980b9;
        }

        .delete-button {
            background-color: transparent;
            color: #94a3b8;
            border: none;
            font-size: 20px;
            cursor: pointer;
            margin-left: 8px;
            transition: color 0.2s;
        }

        .delete-button:hover {
            color: #ef4444;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 24px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .close-button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color: white;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 20px;
        }

        .confirm-modal {
            text-align: center;
            padding: 30px 20px;
        }

        .confirm-icon {
            color: #e74c3c;
            font-size: 48px;
            margin-bottom: 16px;
        }

        .confirm-message {
            font-size: 18px;
            color: #333;
            margin-bottom: 24px;
        }

        .confirm-user {
            font-weight: 600;
            color: #2c3e50;
        }

        .cancel-button {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px 16px;
            font-size: 14px;
            cursor: pointer;
        }

        .save-button {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 16px;
            font-size: 14px;
            cursor: pointer;
        }

        .confirm-delete-button {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 16px;
            font-size: 14px;
            cursor: pointer;
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

        .inline {
            display: inline;
        }

        .login-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .login-btn:hover {
            background-color: #2980b9;
        }

        /* Add this to ensure content doesn't hide behind navbar */
        body {
            padding-top: 80px;
        }

        @media (max-width: 768px) {
            .table-header, .table-row {
                grid-template-columns: 1fr 1.5fr 1.5fr;
            }

            .table-header > div:nth-child(3),
            .table-row > div:nth-child(3) {
                display: none;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .add-button {
                width: 100%;
                justify-content: center;
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
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="login-btn">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h1>User Management</h1>
            <button class="add-button" id="addUserBtn"><i class="fas fa-plus"></i> Add New User</button>
        </div>
        <div class="table-container">
            <div class="table-header">
                <div>User ID</div>
                <div>Name</div>
                <div>Email</div>
                <div>Status</div>
                <div>Actions</div>
            </div>
            <div class="table-body" id="userTableBody">
                <!-- User data will be inserted here -->
            </div>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal" id="userModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Add New User</h2>
                <button class="close-button" id="closeModal">&times;</button>
            </div>
            <form id="userForm">
                <input type="hidden" id="userId" value="">
                <div class="form-group">
                    <label class="form-label" for="userName">Name</label>
                    <input type="text" class="form-control" id="userName" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="userEmail">Email Address</label>
                    <input type="email" class="form-control" id="userEmail" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="userRole">Role</label>
                    <select class="form-select" id="userRole" required>
                        <option value="Admin">Admin</option>
                        <option value="User">User</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="userStatus">Status</label>
                    <select class="form-select" id="userStatus" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Locked">Locked</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="userPassword">Password</label>
                    <input type="password" class="form-control" id="userPassword" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-button" id="cancelModal"><i class="fas fa-times"></i> Cancel</button>
                    <button type="submit" class="save-button"><i class="fas fa-save"></i> Save User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="confirm-modal">
                <div class="confirm-icon">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <div class="confirm-message">
                    Are you sure you want to delete user <span id="deleteUserName" class="confirm-user"></span>?
                </div>
                <div class="modal-footer" style="justify-content: center;">
                    <button id="cancelDelete" class="cancel-button"><i class="fas fa-times"></i> Cancel</button>
                    <button id="confirmDelete" class="confirm-delete-button"><i class="fas fa-trash-alt"></i> Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('CSRF Token:', csrfToken); // Add this line

            // Get current route name
            const currentRoute = window.location.pathname;
            
            // Find and set active link
            const navLinks = document.querySelectorAll('.nav-links a');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentRoute) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            // DOM elements
            const userTableBody = document.getElementById('userTableBody');
            const addUserBtn = document.getElementById('addUserBtn');
            const userModal = document.getElementById('userModal');
            const closeModal = document.getElementById('closeModal');
            const cancelModal = document.getElementById('cancelModal');
            const userForm = document.getElementById('userForm');
            const modalTitle = document.getElementById('modalTitle');
            const notification = document.getElementById('notification');
            const deleteModal = document.getElementById('deleteModal');
            const deleteUserName = document.getElementById('deleteUserName');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');

            // User to delete reference
            let userToDelete = null;

            // Functions
            async function fetchUsers() {
                try {
                    const response = await fetch('/api/users', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || `Failed to fetch users (Status: ${response.status})`);
                    }

                    const users = await response.json();
                    renderUsers(users);
                    return users;
                } catch (error) {
                    console.error('Error:', error);
                    showNotification(`Failed to load users: ${error.message}`, false);
                    return [];
                }
            }

            function renderUsers(users) {
                userTableBody.innerHTML = '';
                users.forEach(user => {
                    const statusClass = `status-${user.status.toLowerCase()}`;
                    const userRow = document.createElement('div');
                    userRow.className = 'table-row';
                    const editButton = `<button class="edit-button" data-id="${user.id}">
                        <i class="fas fa-edit"></i> Edit
                    </button>`;
                    console.log('Creating edit button:', editButton); // Debug line
                    
                    userRow.innerHTML = `
                        <div>${user.id}</div>
                        <div>${user.name}</div>
                        <div>${user.email}</div>
                        <div><span class="status ${statusClass}">${user.status}</span></div>
                        <div class="action-buttons">
                            ${editButton}
                            ${user.status === 'Active' 
                                ? `<button class="deactivate-button" data-id="${user.id}">
                                     <i class="fas fa-ban"></i> Deactivate
                                   </button>`
                                : `<button class="activate-button" data-id="${user.id}">
                                     <i class="fas fa-check"></i> Activate
                                   </button>`
                            }
                            <button class="delete-button" data-id="${user.id}" data-name="${user.name}">✕</button>
                        </div>
                    `;
                    userTableBody.appendChild(userRow);
                });
            }

            function showModal(title) {
                modalTitle.textContent = title;
                userModal.style.display = 'flex';
            }

            function hideModal() {
                userModal.style.display = 'none';
                resetForm();
            }

            function resetForm() {
                const userForm = document.getElementById('userForm');
                userForm.reset();
                document.getElementById('userId').value = '';
                document.getElementById('userPassword').setAttribute('required', 'required');
            }

            function showDeleteConfirmation(userId, userName) {
                userToDelete = userId;
                deleteUserName.textContent = userName;
                deleteModal.style.display = 'flex';
            }

            function hideDeleteModal() {
                deleteModal.style.display = 'none';
                userToDelete = null;
            }

            function showNotification(message, isSuccess = true) {
                notification.textContent = message;
                notification.style.backgroundColor = isSuccess ? '#2ecc71' : '#e74c3c';
                notification.style.display = 'block';

                setTimeout(() => {
                    notification.style.display = 'none';
                }, 3000);
            }

            function attachEventListeners() {
                addUserBtn.addEventListener('click', function() {
                    showModal('Add New User');
                });

                closeModal.addEventListener('click', hideModal);
                cancelModal.addEventListener('click', hideModal);

                cancelDelete.addEventListener('click', hideDeleteModal);
                confirmDelete.addEventListener('click', function() {
                    if (userToDelete) {
                        deleteUser(userToDelete);
                        hideDeleteModal();
                    }
                });

                userForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const userId = document.getElementById('userId').value;
                    const userData = {
                        name: document.getElementById('userName').value.trim(),
                        email: document.getElementById('userEmail').value.trim(),
                        role: document.getElementById('userRole').value,
                        status: document.getElementById('userStatus').value
                    };

                    const password = document.getElementById('userPassword').value;
                    if (password) {
                        userData.password = password;
                    }

                    try {
                        if (userId) {
                            await updateUser(userId, userData);
                        } else {
                            await addUser(userData);
                        }
                        hideModal();
                        await fetchUsers();
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification(error.message, false);
                    }
                });

                // Event delegation for dynamic buttons
                userTableBody.addEventListener('click', async function(e) {
                    const target = e.target.closest('button');
                    if (!target) return;

                    const userId = target.dataset.id;
                    console.log('Button clicked:', target.className, 'User ID:', userId); // Debug line

                    if (target.classList.contains('edit-button')) {
                        console.log('Edit button clicked for user:', userId); // Debug line
                        await editUser(userId);
                    } else if (target.classList.contains('deactivate-button')) {
                        await changeUserStatus(userId, 'Inactive');
                    } else if (target.classList.contains('activate-button')) {
                        await changeUserStatus(userId, 'Active');
                    } else if (target.classList.contains('delete-button')) {
                        const userName = target.dataset.name;
                        showDeleteConfirmation(userId, userName);
                    }
                });
            }

            async function addUser(userData) {
                try {
                    const response = await fetch('/api/users', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(userData),
                        credentials: 'same-origin'
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422) {
                            const errorMessages = Object.values(data.errors).flat().join('\n');
                            throw new Error(errorMessages);
                        }
                        throw new Error(data.message || 'Failed to add user');
                    }

                    showNotification('User added successfully', true);
                    await fetchUsers();
                    hideModal();
                } catch (error) {
                    console.error('Error:', error);
                    showNotification(error.message, false);
                    throw error;
                }
            }

            async function deleteUser(userId) {
                try {
                    const response = await fetch(`/api/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || `Failed to delete user (Status: ${response.status})`);
                    }

                    showNotification('User deleted successfully', true);
                    await fetchUsers(); // Refresh user list
                } catch (error) {
                    console.error('Error:', error);
                    showNotification(`Failed to delete user: ${error.message}`, false);
                }
            }

            async function changeUserStatus(userId, newStatus) {
                try {
                    const response = await fetch(`/api/users/${userId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ status: newStatus }),
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || `Failed to update user status (Status: ${response.status})`);
                    }

                    const data = await response.json();
                    showNotification(`User status changed to ${newStatus} successfully`, true);
                    await fetchUsers(); // Refresh the user list
                } catch (error) {
                    console.error('Error:', error);
                    showNotification(`Failed to change user status: ${error.message}`, false);
                }
            }

            async function editUser(userId) {
                try {
                    console.log('Fetching user data for ID:', userId); // Debug line
                    const response = await fetch(`/api/users/${userId}`, {
                        method: 'GET', // Add explicit method
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        throw new Error(`Failed to fetch user data: ${response.status}`);
                    }

                    const user = await response.json();
                    console.log('Received user data:', user); // Debug line
                    
                    // Populate form fields
                    document.getElementById('userId').value = user.id;
                    document.getElementById('userName').value = user.name;
                    document.getElementById('userEmail').value = user.email;
                    document.getElementById('userRole').value = user.role;
                    document.getElementById('userStatus').value = user.status;
                    
                    // Make password optional for editing
                    const passwordField = document.getElementById('userPassword');
                    passwordField.removeAttribute('required');
                    passwordField.value = ''; // Clear any existing value
                    
                    modalTitle.textContent = 'Edit User';
                    userModal.style.display = 'flex';
                } catch (error) {
                    console.error('Error in editUser:', error); // Debug line
                    showNotification(`Failed to load user data: ${error.message}`, false);
                }
            }

            async function updateUser(userId, userData) {
                try {
                    const response = await fetch(`/api/users/${userId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(userData),
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        const data = await response.json();
                        if (response.status === 422) {
                            showNotification(Object.values(data.errors).flat().join('\n'), false); // Show validation errors
                            return; // Stop execution if there are validation errors
                        }
                        showNotification(data.message || 'Failed to update user', false);
                        return; // Stop execution if there's an error
                    }

                    showNotification('User updated successfully', true);
                    await fetchUsers(); // Refresh the user list
                    hideModal(); // Close the modal
                } catch (error) {
                    console.error('Error:', error);
                    showNotification(`Failed to update user: ${error.message}`, false);
                }
            }

            // Event Listeners
            addUserBtn.addEventListener('click', function() {
                showModal('Add New User');
            });

            closeModal.addEventListener('click', hideModal);
            cancelModal.addEventListener('click', hideModal);

            cancelDelete.addEventListener('click', hideDeleteModal);
            confirmDelete.addEventListener('click', function() {
                if (userToDelete) {
                    deleteUser(userToDelete);
                    hideDeleteModal();
                }
            });

            // Initial render
            attachEventListeners();
            fetchUsers();
        });
    </script>
</body>
</html>