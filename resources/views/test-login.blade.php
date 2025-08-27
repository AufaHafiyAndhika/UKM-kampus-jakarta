<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Login - UKM Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">🧪 Login Testing Dashboard</h1>
            <p class="text-gray-600">Test semua akun yang telah dibuat dan verifikasi fungsi login</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-blue-600" id="total-users">-</div>
                <div class="text-gray-600">Total Users</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-green-600" id="active-users">-</div>
                <div class="text-gray-600">Active Users</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-yellow-600" id="pending-users">-</div>
                <div class="text-gray-600">Pending Users</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-red-600" id="suspended-users">-</div>
                <div class="text-gray-600">Suspended Users</div>
            </div>
        </div>

        <!-- Quick Test Buttons -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">🚀 Quick Test Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button onclick="createAccounts()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-user-plus mr-2"></i>Create New Accounts
                </button>
                <button onclick="runLoginTests()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-vial mr-2"></i>Run Login Tests
                </button>
                <button onclick="loadCredentials()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-key mr-2"></i>Show All Credentials
                </button>
            </div>
        </div>

        <!-- Test Results -->
        <div class="bg-white rounded-lg shadow p-6 mb-8" id="test-results" style="display: none;">
            <h2 class="text-xl font-semibold mb-4">📊 Test Results</h2>
            <div id="test-content"></div>
        </div>

        <!-- Credentials Table -->
        <div class="bg-white rounded-lg shadow p-6" id="credentials-section" style="display: none;">
            <h2 class="text-xl font-semibold mb-4">🔑 Available Credentials</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Password</th>
                            <th class="px-4 py-2 text-left">Role</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody id="credentials-table">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Manual Login Section -->
        <div class="bg-white rounded-lg shadow p-6 mt-8">
            <h2 class="text-xl font-semibold mb-4">🔐 Manual Login Test</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <form id="login-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i>Test Login
                        </button>
                    </form>
                </div>
                <div>
                    <h3 class="font-medium mb-2">Quick Fill Options:</h3>
                    <div class="space-y-2">
                        <button onclick="fillLogin('adminbaru@telkomuniversity.ac.id', 'password123')" 
                                class="w-full text-left px-3 py-2 bg-red-50 hover:bg-red-100 rounded border">
                            👨‍💼 Admin Baru
                        </button>
                        <button onclick="fillLogin('mahasiswabaru@telkomuniversity.ac.id', 'password123')" 
                                class="w-full text-left px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded border">
                            👨‍🎓 Mahasiswa Baru
                        </button>
                        <button onclick="fillLogin('ketuabaru@telkomuniversity.ac.id', 'password123')" 
                                class="w-full text-left px-3 py-2 bg-green-50 hover:bg-green-100 rounded border">
                            👨‍💼 Ketua UKM Baru
                        </button>
                        <button onclick="fillLogin('studentpending@telkomuniversity.ac.id', 'password123')" 
                                class="w-full text-left px-3 py-2 bg-yellow-50 hover:bg-yellow-100 rounded border">
                            ⏳ Student Pending (Should Fail)
                        </button>
                        <button onclick="fillLogin('studentsuspended@telkomuniversity.ac.id', 'password123')" 
                                class="w-full text-left px-3 py-2 bg-red-50 hover:bg-red-100 rounded border">
                            🚫 Student Suspended (Should Fail)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load initial data
        document.addEventListener('DOMContentLoaded', function() {
            loadCredentials();
        });

        async function createAccounts() {
            try {
                const response = await fetch('/create-new-accounts');
                const data = await response.json();
                
                if (data.success) {
                    showNotification('✅ Accounts created successfully!', 'success');
                    updateStatistics(data.statistics);
                    loadCredentials();
                } else {
                    showNotification('❌ Error: ' + data.error, 'error');
                }
            } catch (error) {
                showNotification('❌ Network error: ' + error.message, 'error');
            }
        }

        async function runLoginTests() {
            try {
                const response = await fetch('/test-login');
                const data = await response.json();
                
                document.getElementById('test-results').style.display = 'block';
                
                if (data.success) {
                    displayTestResults(data);
                    showNotification('✅ Login tests completed!', 'success');
                } else {
                    showNotification('❌ Test error: ' + data.error, 'error');
                }
            } catch (error) {
                showNotification('❌ Network error: ' + error.message, 'error');
            }
        }

        async function loadCredentials() {
            try {
                const response = await fetch('/show-credentials');
                const data = await response.json();
                
                if (data.success) {
                    updateStatistics(data.statistics);
                    displayCredentials(data.all_credentials);
                    document.getElementById('credentials-section').style.display = 'block';
                } else {
                    showNotification('❌ Error loading credentials: ' + data.error, 'error');
                }
            } catch (error) {
                showNotification('❌ Network error: ' + error.message, 'error');
            }
        }

        function updateStatistics(stats) {
            document.getElementById('total-users').textContent = stats.total_users || 0;
            document.getElementById('active-users').textContent = stats.by_status?.active || 0;
            document.getElementById('pending-users').textContent = stats.by_status?.pending || 0;
            document.getElementById('suspended-users').textContent = stats.by_status?.suspended || 0;
        }

        function displayTestResults(data) {
            const content = document.getElementById('test-content');
            let html = `
                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-semibold">Test Summary</h3>
                    <p>Total Tests: ${data.test_summary.total_tests}</p>
                    <p>Passed: ${data.test_summary.passed_tests}</p>
                    <p>Failed: ${data.test_summary.failed_tests}</p>
                    <p>Success Rate: ${data.test_summary.success_rate}</p>
                </div>
                <div class="space-y-2">
            `;
            
            data.detailed_results.forEach(result => {
                const statusClass = result.test_status.includes('✅') ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
                html += `
                    <div class="p-3 border rounded ${statusClass}">
                        <div class="font-medium">${result.email} (${result.actual_role})</div>
                        <div class="text-sm text-gray-600">
                            Status: ${result.actual_status} | 
                            Password: ${result.password_check} | 
                            Auth: ${result.auth_attempt} | 
                            Result: ${result.test_status}
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            content.innerHTML = html;
        }

        function displayCredentials(credentials) {
            const tbody = document.getElementById('credentials-table');
            let html = '';
            
            credentials.forEach(cred => {
                const statusClass = cred.status === 'active' ? 'text-green-600' : 
                                   cred.status === 'pending' ? 'text-yellow-600' : 'text-red-600';
                const roleIcon = cred.role === 'admin' ? '👨‍💼' : 
                                cred.role === 'ketua_ukm' ? '👨‍💼' : '👨‍🎓';
                
                html += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">${roleIcon} ${cred.name}</td>
                        <td class="px-4 py-2 font-mono text-sm">${cred.email}</td>
                        <td class="px-4 py-2 font-mono text-sm">${cred.password}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">${cred.role}</span>
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs ${statusClass}">${cred.status}</span>
                        </td>
                        <td class="px-4 py-2">
                            <button onclick="fillLogin('${cred.email}', '${cred.password}')" 
                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-sign-in-alt mr-1"></i>Test
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
        }

        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Handle manual login form
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (email && password) {
                // Redirect to actual login page with pre-filled data
                const loginUrl = '/login';
                window.open(loginUrl, '_blank');
                showNotification('🔗 Opening login page in new tab', 'success');
            } else {
                showNotification('❌ Please fill in both email and password', 'error');
            }
        });
    </script>
</body>
</html>
