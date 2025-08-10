<!DOCTYPE html>
<html>
<head>
    <title>User Role API</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .section { margin-bottom: 30px; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        button { padding: 5px 10px; margin: 5px; cursor: pointer; }
        input, select { padding: 5px; margin: 5px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
<h1>User Role API Interface</h1>

<div class="section">
    <h2>Roles</h2>
    <div>
        <input type="text" id="roleName" placeholder="Role name">
        <button onclick="createRole()">Create Role</button>
    </div>
    <div id="rolesList"></div>
</div>

<div class="section">
    <h2>Users</h2>
    <div>
        <input type="text" id="userName" placeholder="User name">
        <select id="userRole">
            <!-- Roles will be populated here -->
        </select>
        <button onclick="createUser()">Create User</button>
    </div>
    <div id="usersList"></div>
</div>

<script>
    // Base API URL
    const API_URL = '/api';

    // Load roles and users on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadRoles();
        loadUsers();
    });

    // Role functions
    async function loadRoles() {
        try {
            const response = await axios.get(`${API_URL}/roles`);
            const roles = response.data;

            // Display roles
            let rolesHtml = '<h3>Role List</h3><ul>';
            roles.forEach(role => {
                rolesHtml += `<li>${role.name}
                        <button onclick="editRole(${role.id}, '${role.name}')">Edit</button>
                        <button onclick="deleteRole(${role.id})">Delete</button>
                    </li>`;
            });
            rolesHtml += '</ul>';
            document.getElementById('rolesList').innerHTML = rolesHtml;

            // Populate role dropdown for users
            let roleOptions = '<option value="">Select Role</option>';
            roles.forEach(role => {
                roleOptions += `<option value="${role.id}">${role.name}</option>`;
            });
            document.getElementById('userRole').innerHTML = roleOptions;
        } catch (error) {
            console.error('Error loading roles:', error);
            alert('Error loading roles');
        }
    }

    async function createRole() {
        const name = document.getElementById('roleName').value.trim();
        if (!name) {
            alert('Role name is required');
            return;
        }

        try {
            await axios.post(`${API_URL}/roles`, { name });
            document.getElementById('roleName').value = '';
            loadRoles();
        } catch (error) {
            console.error('Error creating role:', error);
            alert('Error creating role');
        }
    }

    async function editRole(id, currentName) {
        const newName = prompt('Enter new role name:', currentName);
        if (newName && newName !== currentName) {
            try {
                await axios.put(`${API_URL}/roles/${id}`, { name: newName });
                loadRoles();
            } catch (error) {
                console.error('Error updating role:', error);
                alert('Error updating role');
            }
        }
    }

    async function deleteRole(id) {
        if (confirm('Are you sure you want to delete this role?')) {
            try {
                await axios.delete(`${API_URL}/roles/${id}`);
                loadRoles();
            } catch (error) {
                console.error('Error deleting role:', error);
                alert(error.response.data.message || 'Error deleting role');
            }
        }
    }

    // User functions
    async function loadUsers() {
        try {
            const response = await axios.get(`${API_URL}/clients`);
            const users = response.data;

            let usersHtml = '<h3>User List</h3><ul>';
            users.forEach(user => {
                usersHtml += `<li>${user.name} (${user.email}) - ${user.role.name}
                        <button onclick="editUser(${user.id}, '${user.name}', '${user.email}', ${user.role_id})">Edit</button>
                        <button onclick="deleteUser(${user.id})">Delete</button>
                    </li>`;
            });
            usersHtml += '</ul>';
            document.getElementById('usersList').innerHTML = usersHtml;
        } catch (error) {
            console.error('Error loading users:', error);
            alert('Error loading users');
        }
    }

    async function createUser() {
        const name = document.getElementById('userName').value.trim();
        const email = document.getElementById('userEmail').value.trim();
        const roleId = document.getElementById('userRole').value;

        if (!name || !email || !roleId) {
            alert('All fields are required');
            return;
        }

        try {
            await axios.post(`${API_URL}/clients`, { name, email, role_id: roleId });
            document.getElementById('userName').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userRole').value = '';
            loadUsers();
        } catch (error) {
            console.error('Error creating user:', error);
            alert('Error creating user');
        }
    }

    async function editUser(id, currentName, currentEmail, currentRoleId) {
        const newName = prompt('Enter new name:', currentName);
        const newEmail = prompt('Enter new email:', currentEmail);

        if ((newName && newName !== currentName) || (newEmail && newEmail !== currentEmail)) {
            try {
                const roleResponse = await axios.get(`${API_URL}/roles`);
                const roles = roleResponse.data;

                let roleOptions = '';
                roles.forEach(role => {
                    roleOptions += `${role.id}: ${role.name}\n`;
                });

                const newRoleId = prompt(`Enter new role ID (current: ${currentRoleId}):\n${roleOptions}`, currentRoleId);

                if (newRoleId) {
                    await axios.put(`${API_URL}/clients/${id}`, {
                        name: newName || currentName,
                        email: newEmail || currentEmail,
                        role_id: newRoleId || currentRoleId
                    });
                    loadUsers();
                }
            } catch (error) {
                console.error('Error updating user:', error);
                alert('Error updating user');
            }
        }
    }

    async function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            try {
                await axios.delete(`${API_URL}/clients/${id}`);
                loadUsers();
            } catch (error) {
                console.error('Error deleting user:', error);
                alert('Error deleting user');
            }
        }
    }
</script>
</body>
</html>
