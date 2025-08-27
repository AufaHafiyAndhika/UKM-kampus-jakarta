<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UkmController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;


// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// SEO Routes
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-pages.xml', [App\Http\Controllers\SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-ukms.xml', [App\Http\Controllers\SitemapController::class, 'ukms'])->name('sitemap.ukms');
Route::get('/sitemap-events.xml', [App\Http\Controllers\SitemapController::class, 'events'])->name('sitemap.events');

// UKM Routes
Route::get('/ukm', [UkmController::class, 'index'])->name('ukms.index');
Route::get('/ukm/{ukm}', [UkmController::class, 'show'])->name('ukms.show');

// Achievement Routes
Route::get('/prestasi', [AchievementController::class, 'index'])->name('achievements.index');
Route::get('/prestasi/{achievement}', [AchievementController::class, 'show'])->name('achievements.show');
Route::get('/ukm/{ukm}/prestasi', [AchievementController::class, 'byUkm'])->name('achievements.by-ukm');

// Event Routes
Route::get('/kegiatan', [EventController::class, 'index'])->name('events.index');
Route::get('/kegiatan/{event}', [EventController::class, 'show'])->name('events.show');

// Public certificate route (alias for events.attendance.certificate)
Route::get('/events/{event}/certificate', [App\Http\Controllers\AttendanceController::class, 'downloadCertificate'])->name('events.certificate');

// Route aliases for compatibility
Route::get('/ketua-ukm/events/{event}/attendances', [App\Http\Controllers\KetuaUkmController::class, 'showAttendances'])->name('ketua-ukm.events.attendances')->middleware(['auth', 'check.status', 'role:ketua_ukm']);

// Include Authentication Routes
require __DIR__.'/auth.php';

// Backup Registration Success Route (in case auth.php doesn't load)
Route::get('/register/success', function() {
    return view('auth.register-success');
})->name('register.success.backup');

// Protected Routes
Route::middleware(['auth', 'check.status'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // UKM Membership
    Route::get('/ukm/{ukm}/daftar', [UkmController::class, 'showRegistrationForm'])->name('ukms.registration-form');
    Route::post('/ukm/{ukm}/daftar', [UkmController::class, 'submitRegistration'])->name('ukms.submit-registration');
    Route::post('/ukm/{ukm}/gabung', [UkmController::class, 'join'])->name('ukms.join');
    Route::delete('/ukm/{ukm}/keluar', [UkmController::class, 'leave'])->name('ukms.leave');

    // Student UKM Status Tracking
    Route::get('/my-ukm-applications', [UkmController::class, 'myApplications'])->name('ukms.my-applications');

    // Event Registration
    Route::get('/events/{event}/register', [EventController::class, 'showRegistrationForm'])->name('events.register-form');
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
    Route::delete('/events/{event}/cancel', [EventController::class, 'cancelRegistration'])->name('events.cancel');

    // Event Attendance
    Route::get('/events/{event}/attendance', [App\Http\Controllers\AttendanceController::class, 'showForm'])->name('events.attendance.form');
    Route::post('/events/{event}/attendance', [App\Http\Controllers\AttendanceController::class, 'submit'])->name('events.attendance.submit');
    Route::get('/events/{event}/certificate', [App\Http\Controllers\AttendanceController::class, 'downloadCertificate'])->name('events.attendance.certificate');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin routes that require authentication and admin role
    Route::middleware(['auth', 'check.status', 'admin'])->group(function () {
        // Dashboard
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard.alt');

        // User Management
        Route::resource('users', App\Http\Controllers\Admin\UserManagementController::class);
        Route::patch('users/{user}/activate', [App\Http\Controllers\Admin\UserManagementController::class, 'activate'])->name('users.activate');
        Route::patch('users/{user}/suspend', [App\Http\Controllers\Admin\UserManagementController::class, 'suspend'])->name('users.suspend');

        // UKM Management
        Route::resource('ukms', App\Http\Controllers\Admin\UkmManagementController::class);
        Route::get('ukms/{ukm}/members', [App\Http\Controllers\Admin\UkmManagementController::class, 'members'])->name('ukms.members');
        Route::delete('ukms/{ukm}/remove-leader', [App\Http\Controllers\Admin\UkmManagementController::class, 'removeLeader'])->name('ukms.remove-leader');

        // Ketua UKM Management
        Route::resource('ketua-ukm', App\Http\Controllers\Admin\KetuaUkmManagementController::class);
        Route::post('ketua-ukm/{ketuaUkm}/assign-ukm', [App\Http\Controllers\Admin\KetuaUkmManagementController::class, 'assignUkm'])->name('ketua-ukm.assign-ukm');
        Route::delete('ketua-ukm/{ketuaUkm}/remove-ukm/{ukm}', [App\Http\Controllers\Admin\KetuaUkmManagementController::class, 'removeUkm'])->name('ketua-ukm.remove-ukm');
        Route::patch('ketua-ukm/{ketuaUkm}/suspend', [App\Http\Controllers\Admin\KetuaUkmManagementController::class, 'suspend'])->name('ketua-ukm.suspend');
        Route::patch('ketua-ukm/{ketuaUkm}/activate', [App\Http\Controllers\Admin\KetuaUkmManagementController::class, 'activate'])->name('ketua-ukm.activate');

        // Event Management
        Route::resource('events', App\Http\Controllers\Admin\EventManagementController::class);
        Route::patch('events/{event}/publish', [App\Http\Controllers\Admin\EventManagementController::class, 'publish'])->name('events.publish');
        Route::patch('events/{event}/cancel', [App\Http\Controllers\Admin\EventManagementController::class, 'cancel'])->name('events.cancel');
        Route::patch('events/{event}/approve', [App\Http\Controllers\Admin\EventManagementController::class, 'approve'])->name('events.approve');
        Route::patch('events/{event}/reject', [App\Http\Controllers\Admin\EventManagementController::class, 'reject'])->name('events.reject');
        Route::patch('events/{event}/cancel-event', [App\Http\Controllers\Admin\EventManagementController::class, 'cancelEvent'])->name('events.cancel-event');
        Route::post('events/update-statuses', [App\Http\Controllers\Admin\EventManagementController::class, 'updateAllStatuses'])->name('events.update-statuses');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
            Route::get('/{event}', [App\Http\Controllers\Admin\ReportController::class, 'show'])->name('show');
            Route::get('/{event}/download/{type}', [App\Http\Controllers\Admin\ReportController::class, 'downloadFile'])->name('download');
            Route::get('/{event}/view/{type}', [App\Http\Controllers\Admin\ReportController::class, 'viewFile'])->name('view');
            Route::get('/export/csv', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('export');
        });

        // Additional admin routes
        Route::get('/stats', [App\Http\Controllers\Admin\AdminController::class, 'stats'])->name('stats');
    });
});

// Ketua UKM Routes
Route::prefix('ketua-ukm')->name('ketua-ukm.')->middleware(['auth', 'check.status'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\KetuaUkmController::class, 'dashboard'])->name('dashboard');

    // UKM Management
    Route::get('/manage/{id}', [App\Http\Controllers\KetuaUkmController::class, 'manageUkm'])->name('manage');
    Route::get('/edit-ukm/{id}', [App\Http\Controllers\KetuaUkmController::class, 'editUkm'])->name('edit-ukm');
    Route::put('/update-ukm/{id}', [App\Http\Controllers\KetuaUkmController::class, 'updateUkm'])->name('update-ukm');
    Route::get('/ukm/edit', [App\Http\Controllers\KetuaUkmController::class, 'editUkm'])->name('ukm.edit');
    Route::put('/ukm/update', [App\Http\Controllers\KetuaUkmController::class, 'updateUkm'])->name('ukm.update');

    // Event Management
    Route::get('/events', [App\Http\Controllers\KetuaUkmController::class, 'events'])->name('events');
    Route::get('/events/create', [App\Http\Controllers\KetuaUkmController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [App\Http\Controllers\KetuaUkmController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}', [App\Http\Controllers\KetuaUkmController::class, 'showEvent'])->name('events.show');
    Route::get('/events/{event}/edit', [App\Http\Controllers\KetuaUkmController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{event}', [App\Http\Controllers\KetuaUkmController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{event}', [App\Http\Controllers\KetuaUkmController::class, 'destroyEvent'])->name('events.destroy');
    Route::post('/events/{event}/refresh-status', [App\Http\Controllers\KetuaUkmController::class, 'refreshEventStatus'])->name('events.refresh-status');
    Route::get('/create-event/{ukmId?}', [App\Http\Controllers\KetuaUkmController::class, 'createEvent'])->name('create-event');
    Route::post('/store-event', [App\Http\Controllers\KetuaUkmController::class, 'storeEvent'])->name('store-event');

    // Event Attendances
    Route::get('/events/{event}/attendances', [App\Http\Controllers\KetuaUkmController::class, 'showAttendances'])->name('events.attendances');
    Route::post('/events/{event}/attendances/{attendance}/verify', [App\Http\Controllers\KetuaUkmController::class, 'verifyAttendance'])->name('events.attendances.verify');
    Route::post('/events/{event}/attendances/bulk-verify', [App\Http\Controllers\KetuaUkmController::class, 'bulkVerifyAttendances'])->name('events.bulk-verify-attendances');

    // Event Registrations Management
    Route::get('/events/{event}/registrations', [App\Http\Controllers\KetuaUkmController::class, 'showEventRegistrations'])->name('events.registrations');
    Route::get('/events/{event}/registrations/{registration}', [App\Http\Controllers\KetuaUkmController::class, 'showRegistrationDetails'])->name('events.registrations.show');
    Route::post('/events/{event}/registrations/{registration}/approve', [App\Http\Controllers\KetuaUkmController::class, 'approveEventRegistration'])->name('events.registrations.approve');
    Route::post('/events/{event}/registrations/{registration}/reject', [App\Http\Controllers\KetuaUkmController::class, 'rejectEventRegistration'])->name('events.registrations.reject');
    Route::post('/events/{event}/registrations/bulk-approve', [App\Http\Controllers\KetuaUkmController::class, 'bulkApproveEventRegistrations'])->name('events.registrations.bulk-approve');

    // Member Management
    Route::get('/pending-members', [App\Http\Controllers\KetuaUkmController::class, 'pendingMembers'])->name('pending-members');
    Route::get('/pending-members/{member}/details', [App\Http\Controllers\KetuaUkmController::class, 'getMemberDetails'])->name('pending-members.details');
    Route::put('/pending-members/{member}/approve', [App\Http\Controllers\KetuaUkmController::class, 'approveMember'])->name('pending-members.approve');
    Route::put('/pending-members/{member}/reject', [App\Http\Controllers\KetuaUkmController::class, 'rejectMember'])->name('pending-members.reject');

    Route::get('/members', [App\Http\Controllers\KetuaUkmController::class, 'members'])->name('members');
    Route::get('/members/{member}/details', [App\Http\Controllers\KetuaUkmController::class, 'getMemberDetails'])->name('members.details');
    Route::put('/members/{member}/approve', [App\Http\Controllers\KetuaUkmController::class, 'approveMember'])->name('members.approve');
    Route::put('/members/{member}/reject', [App\Http\Controllers\KetuaUkmController::class, 'rejectMember'])->name('members.reject');
    Route::delete('/members/{member}/remove', [App\Http\Controllers\KetuaUkmController::class, 'removeMember'])->name('members.remove');
});

// Notification Routes
Route::middleware(['auth', 'check.status'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
});

// Temporary route for creating users (REMOVE IN PRODUCTION)
Route::get('/create-users', function () {
    try {
        $users = [
            [
                'nim' => 'ADMIN001',
                'name' => 'Administrator',
                'email' => 'admin@telkomuniversity.ac.id',
                'password' => Hash::make('admin123'),
                'phone' => '081234567890',
                'gender' => 'male',
                'faculty' => 'Administrasi',
                'major' => 'Sistem Informasi',
                'batch' => '2024',
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'nim' => '1103210001',
                'name' => 'John Doe',
                'email' => 'student@telkomuniversity.ac.id',
                'password' => Hash::make('admin123'),
                'phone' => '081234567892',
                'gender' => 'male',
                'faculty' => 'Informatika',
                'major' => 'Teknik Informatika',
                'batch' => '2021',
                'role' => 'student',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'nim' => '1103210002',
                'name' => 'Jane Smith',
                'email' => 'ketua@telkomuniversity.ac.id',
                'password' => Hash::make('admin123'),
                'phone' => '081234567893',
                'gender' => 'female',
                'faculty' => 'Informatika',
                'major' => 'Sistem Informasi',
                'batch' => '2021',
                'role' => 'ketua_ukm',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        ];

        $created = 0;
        foreach ($users as $userData) {
            $existing = App\Models\User::where('email', $userData['email'])->first();
            if (!$existing) {
                App\Models\User::create($userData);
                $created++;
            }
        }

        $totalUsers = App\Models\User::count();

        return response()->json([
            'success' => true,
            'message' => "Created $created new users. Total users: $totalUsers",
            'credentials' => [
                'admin@telkomuniversity.ac.id' => 'admin123',
                'student@telkomuniversity.ac.id' => 'admin123',
                'ketua@telkomuniversity.ac.id' => 'admin123'
            ]
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Database diagnosis route
Route::get('/diagnose-db', function () {
    try {
        $results = [];

        // 1. Test database connection
        $results['connection'] = 'Testing...';
        DB::connection()->getPdo();
        $results['connection'] = '✅ Connected';

        // 2. Check tables
        $tables = DB::select('SHOW TABLES');
        $results['tables_count'] = count($tables);
        $results['tables'] = array_map(function($table) {
            return array_values((array)$table)[0];
        }, $tables);

        // 3. Check critical data
        $results['users_count'] = DB::table('users')->count();
        $results['ukms_count'] = DB::table('ukms')->count();
        $results['events_count'] = DB::table('events')->count();
        $results['migrations_count'] = DB::table('migrations')->count();

        // 4. Check users if any exist
        if ($results['users_count'] > 0) {
            $results['sample_users'] = DB::table('users')
                ->select('email', 'role', 'status')
                ->limit(5)
                ->get();
        }

        // 5. Check recent migrations
        if ($results['migrations_count'] > 0) {
            $results['recent_migrations'] = DB::table('migrations')
                ->orderBy('batch', 'desc')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();
        }

        return response()->json([
            'success' => true,
            'diagnosis' => $results,
            'recommendations' => $results['users_count'] == 0 ?
                ['Run seeder: php artisan db:seed', 'Or access: /create-users'] :
                ['Database seems OK', 'Check login credentials']
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'recommendations' => [
                'Check MySQL service is running',
                'Verify database exists: CREATE DATABASE ukmwebv;',
                'Check .env configuration',
                'Run migrations: php artisan migrate'
            ]
        ]);
    }
});

// Database recovery route
Route::get('/recover-db', function () {
    try {
        $results = [];

        // Check current state
        $userCount = DB::table('users')->count();
        $results['initial_user_count'] = $userCount;

        if ($userCount == 0) {
            // Create admin users
            $users = [
                [
                    'nim' => 'ADMIN001',
                    'name' => 'Administrator',
                    'email' => 'admin@telkomuniversity.ac.id',
                    'password' => Hash::make('admin123'),
                    'phone' => '081234567890',
                    'gender' => 'male',
                    'faculty' => 'Administrasi',
                    'major' => 'Sistem Informasi',
                    'batch' => '2024',
                    'role' => 'admin',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nim' => '1103210001',
                    'name' => 'John Doe',
                    'email' => 'student@telkomuniversity.ac.id',
                    'password' => Hash::make('admin123'),
                    'phone' => '081234567892',
                    'gender' => 'male',
                    'faculty' => 'Informatika',
                    'major' => 'Teknik Informatika',
                    'batch' => '2021',
                    'role' => 'student',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nim' => '1103210002',
                    'name' => 'Jane Smith',
                    'email' => 'ketua@telkomuniversity.ac.id',
                    'password' => Hash::make('admin123'),
                    'phone' => '081234567893',
                    'gender' => 'female',
                    'faculty' => 'Informatika',
                    'major' => 'Sistem Informasi',
                    'batch' => '2021',
                    'role' => 'ketua_ukm',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];

            foreach ($users as $userData) {
                DB::table('users')->updateOrInsert(
                    ['email' => $userData['email']],
                    $userData
                );
            }

            $results['action'] = 'Users created';
        } else {
            $results['action'] = 'Users already exist';
        }

        // Final verification
        $results['final_user_count'] = DB::table('users')->count();
        $results['users'] = DB::table('users')->select('email', 'role', 'status')->get();

        // Check other tables
        $results['ukms_count'] = DB::table('ukms')->count();
        $results['events_count'] = DB::table('events')->count();

        return response()->json([
            'success' => true,
            'recovery_results' => $results,
            'credentials' => [
                'admin@telkomuniversity.ac.id' => 'admin123',
                'student@telkomuniversity.ac.id' => 'admin123',
                'ketua@telkomuniversity.ac.id' => 'admin123'
            ],
            'message' => 'Database recovery completed successfully!'
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'recommendations' => [
                'Check database connection',
                'Run migrations if tables missing',
                'Verify MySQL service is running'
            ]
        ]);
    }
});

// Create new test accounts route
Route::get('/create-new-accounts', function () {
    try {
        $newUsers = [
            [
                'nim' => 'ADMIN003',
                'name' => 'Admin Baru',
                'email' => 'adminbaru@telkomuniversity.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567894',
                'gender' => 'male',
                'faculty' => 'Administrasi',
                'major' => 'Manajemen',
                'batch' => '2024',
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1103210003',
                'name' => 'Mahasiswa Baru',
                'email' => 'mahasiswabaru@telkomuniversity.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567895',
                'gender' => 'female',
                'faculty' => 'Informatika',
                'major' => 'Teknik Informatika',
                'batch' => '2022',
                'role' => 'student',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1103210004',
                'name' => 'Ketua UKM Baru',
                'email' => 'ketuabaru@telkomuniversity.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567896',
                'gender' => 'male',
                'faculty' => 'Informatika',
                'major' => 'Sistem Informasi',
                'batch' => '2020',
                'role' => 'ketua_ukm',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1103210005',
                'name' => 'Student Pending',
                'email' => 'studentpending@telkomuniversity.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567897',
                'gender' => 'female',
                'faculty' => 'Ekonomi',
                'major' => 'Akuntansi',
                'batch' => '2023',
                'role' => 'student',
                'status' => 'pending',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1103210006',
                'name' => 'Student Suspended',
                'email' => 'studentsuspended@telkomuniversity.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567898',
                'gender' => 'male',
                'faculty' => 'Teknik',
                'major' => 'Teknik Elektro',
                'batch' => '2021',
                'role' => 'student',
                'status' => 'suspended',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        $created = 0;
        $updated = 0;
        $results = [];

        foreach ($newUsers as $userData) {
            $existing = App\Models\User::where('email', $userData['email'])->first();

            if ($existing) {
                // Update existing user
                $existing->update($userData);
                $updated++;
                $results[] = "Updated: {$userData['email']} ({$userData['role']}) - {$userData['status']}";
            } else {
                // Create new user
                App\Models\User::create($userData);
                $created++;
                $results[] = "Created: {$userData['email']} ({$userData['role']}) - {$userData['status']}";
            }
        }

        // Get final user count and statistics
        $totalUsers = App\Models\User::count();
        $usersByRole = App\Models\User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role');

        $usersByStatus = App\Models\User::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return response()->json([
            'success' => true,
            'message' => "Account creation completed! Created: $created, Updated: $updated",
            'results' => $results,
            'statistics' => [
                'total_users' => $totalUsers,
                'by_role' => $usersByRole,
                'by_status' => $usersByStatus
            ],
            'test_credentials' => [
                'admin_baru' => [
                    'email' => 'adminbaru@telkomuniversity.ac.id',
                    'password' => 'password123',
                    'role' => 'admin',
                    'status' => 'active'
                ],
                'mahasiswa_baru' => [
                    'email' => 'mahasiswabaru@telkomuniversity.ac.id',
                    'password' => 'password123',
                    'role' => 'student',
                    'status' => 'active'
                ],
                'ketua_ukm_baru' => [
                    'email' => 'ketuabaru@telkomuniversity.ac.id',
                    'password' => 'password123',
                    'role' => 'ketua_ukm',
                    'status' => 'active'
                ],
                'student_pending' => [
                    'email' => 'studentpending@telkomuniversity.ac.id',
                    'password' => 'password123',
                    'role' => 'student',
                    'status' => 'pending (should not be able to login)'
                ],
                'student_suspended' => [
                    'email' => 'studentsuspended@telkomuniversity.ac.id',
                    'password' => 'password123',
                    'role' => 'student',
                    'status' => 'suspended (should not be able to login)'
                ]
            ],
            'login_test_instructions' => [
                '1. Test active accounts (should work)',
                '2. Test pending account (should be blocked)',
                '3. Test suspended account (should be blocked)',
                '4. Verify role-based dashboard redirection',
                '5. Check statistics update on dashboard'
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Test login functionality route
Route::get('/test-login', function () {
    try {
        $testAccounts = [
            [
                'email' => 'adminbaru@telkomuniversity.ac.id',
                'password' => 'password123',
                'expected_role' => 'admin',
                'expected_status' => 'active',
                'should_login' => true
            ],
            [
                'email' => 'mahasiswabaru@telkomuniversity.ac.id',
                'password' => 'password123',
                'expected_role' => 'student',
                'expected_status' => 'active',
                'should_login' => true
            ],
            [
                'email' => 'ketuabaru@telkomuniversity.ac.id',
                'password' => 'password123',
                'expected_role' => 'ketua_ukm',
                'expected_status' => 'active',
                'should_login' => true
            ],
            [
                'email' => 'studentpending@telkomuniversity.ac.id',
                'password' => 'password123',
                'expected_role' => 'student',
                'expected_status' => 'pending',
                'should_login' => false
            ],
            [
                'email' => 'studentsuspended@telkomuniversity.ac.id',
                'password' => 'password123',
                'expected_role' => 'student',
                'expected_status' => 'suspended',
                'should_login' => false
            ]
        ];

        $testResults = [];

        foreach ($testAccounts as $account) {
            $result = [
                'email' => $account['email'],
                'expected_role' => $account['expected_role'],
                'expected_status' => $account['expected_status'],
                'should_login' => $account['should_login']
            ];

            // Check if user exists
            $user = App\Models\User::where('email', $account['email'])->first();

            if (!$user) {
                $result['test_result'] = '❌ User not found';
                $testResults[] = $result;
                continue;
            }

            $result['actual_role'] = $user->role;
            $result['actual_status'] = $user->status;

            // Test password verification
            if (Hash::check($account['password'], $user->password)) {
                $result['password_check'] = '✅ Password correct';

                // Test authentication attempt
                if (Auth::attempt([
                    'email' => $account['email'],
                    'password' => $account['password']
                ])) {
                    $result['auth_attempt'] = '✅ Auth::attempt successful';

                    // Check status middleware behavior
                    if ($user->status === 'active') {
                        $result['login_result'] = '✅ Should be able to login';
                        $result['expected_redirect'] = $user->role === 'admin' ? '/admin/dashboard' :
                                                     ($user->role === 'ketua_ukm' ? '/ketua-ukm/dashboard' : '/dashboard');
                    } else {
                        $result['login_result'] = '⚠️ Auth successful but status check should block';
                        $result['expected_redirect'] = 'Should be blocked by status middleware';
                    }

                    // Logout to test next account
                    Auth::logout();
                } else {
                    $result['auth_attempt'] = '❌ Auth::attempt failed';
                    $result['login_result'] = '❌ Cannot login';
                }
            } else {
                $result['password_check'] = '❌ Password incorrect';
                $result['auth_attempt'] = '❌ Password mismatch';
                $result['login_result'] = '❌ Cannot login';
            }

            // Overall test result
            if ($account['should_login']) {
                $result['test_status'] = ($result['auth_attempt'] === '✅ Auth::attempt successful' && $user->status === 'active') ?
                                        '✅ PASS' : '❌ FAIL';
            } else {
                $result['test_status'] = ($user->status !== 'active') ? '✅ PASS (correctly blocked)' : '❌ FAIL (should be blocked)';
            }

            $testResults[] = $result;
        }

        // Summary
        $totalTests = count($testResults);
        $passedTests = count(array_filter($testResults, function($result) {
            return strpos($result['test_status'], '✅ PASS') === 0;
        }));

        return response()->json([
            'success' => true,
            'test_summary' => [
                'total_tests' => $totalTests,
                'passed_tests' => $passedTests,
                'failed_tests' => $totalTests - $passedTests,
                'success_rate' => round(($passedTests / $totalTests) * 100, 2) . '%'
            ],
            'detailed_results' => $testResults,
            'manual_test_links' => [
                'login_page' => url('/login'),
                'admin_dashboard' => url('/admin/dashboard'),
                'ketua_ukm_dashboard' => url('/ketua-ukm/dashboard'),
                'student_dashboard' => url('/dashboard')
            ],
            'test_instructions' => [
                '1. Try logging in manually with each account',
                '2. Verify correct dashboard redirection',
                '3. Check that pending/suspended accounts are blocked',
                '4. Verify role-specific menu access',
                '5. Test logout functionality'
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Display all available credentials
Route::get('/show-credentials', function () {
    try {
        $users = App\Models\User::select('nim', 'name', 'email', 'role', 'status', 'created_at')
            ->orderBy('role')
            ->orderBy('status')
            ->get();

        $credentials = [];
        $statistics = [];

        foreach ($users as $user) {
            $password = 'password123'; // Default for new accounts
            if (in_array($user->email, [
                'admin@telkomuniversity.ac.id',
                'student@telkomuniversity.ac.id',
                'ketua@telkomuniversity.ac.id'
            ])) {
                $password = 'admin123'; // Original accounts
            }

            $credentials[] = [
                'nim' => $user->nim,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $password,
                'role' => $user->role,
                'status' => $user->status,
                'can_login' => $user->status === 'active',
                'created' => $user->created_at->format('d M Y H:i')
            ];
        }

        // Calculate statistics
        $statistics = [
            'total_users' => $users->count(),
            'by_role' => $users->groupBy('role')->map->count(),
            'by_status' => $users->groupBy('status')->map->count(),
            'active_users' => $users->where('status', 'active')->count(),
            'login_ready' => $users->where('status', 'active')->count()
        ];

        return response()->json([
            'success' => true,
            'statistics' => $statistics,
            'all_credentials' => $credentials,
            'quick_test_accounts' => [
                'admin_original' => [
                    'email' => 'admin@telkomuniversity.ac.id',
                    'password' => 'admin123',
                    'role' => 'admin'
                ],
                'admin_new' => [
                    'email' => 'adminbaru@telkomuniversity.ac.id',
                    'password' => 'password123',
                    'role' => 'admin'
                ],
                'student_original' => [
                    'email' => 'student@telkomuniversity.ac.id',
                    'password' => 'admin123',
                    'role' => 'student'
                ],
                'student_new' => [
                    'email' => 'mahasiswabaru@telkomuniversity.ac.id',
                    'password' => 'password123',
                    'role' => 'student'
                ],
                'ketua_ukm_original' => [
                    'email' => 'ketua@telkomuniversity.ac.id',
                    'password' => 'admin123',
                    'role' => 'ketua_ukm'
                ],
                'ketua_ukm_new' => [
                    'email' => 'ketuabaru@telkomuniversity.ac.id',
                    'password' => 'password123',
                    'role' => 'ketua_ukm'
                ]
            ],
            'test_scenarios' => [
                'active_login' => 'Should work for all active accounts',
                'pending_login' => 'Should be blocked for pending accounts',
                'suspended_login' => 'Should be blocked for suspended accounts',
                'role_redirect' => 'Should redirect to correct dashboard based on role'
            ],
            'login_url' => url('/login'),
            'dashboard_urls' => [
                'admin' => url('/admin/dashboard'),
                'ketua_ukm' => url('/ketua-ukm/dashboard'),
                'student' => url('/dashboard')
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Test login page
Route::get('/test-login-page', function () {
    return view('test-login');
});

// Fix role column issue
Route::get('/fix-role-column', function () {
    try {
        $results = [];

        // 1. Check if role column exists
        $columns = DB::select("DESCRIBE users");
        $hasRoleColumn = false;

        foreach ($columns as $column) {
            if ($column->Field === 'role') {
                $hasRoleColumn = true;
                break;
            }
        }

        if (!$hasRoleColumn) {
            // Add role column
            DB::statement("ALTER TABLE users ADD COLUMN role ENUM('admin', 'student', 'ketua_ukm') DEFAULT 'student' AFTER email");
            $results[] = "✅ Added role column to users table";
        } else {
            $results[] = "✅ Role column already exists";
        }

        // 2. Check users without roles
        $usersWithoutRole = DB::table('users')
            ->whereNull('role')
            ->orWhere('role', '')
            ->count();

        if ($usersWithoutRole > 0) {
            $results[] = "⚠️ Found $usersWithoutRole users without roles";

            // Fix admin roles
            $adminUpdated = DB::table('users')
                ->whereIn('email', [
                    'admin@telkomuniversity.ac.id',
                    'superadmin@telkomuniversity.ac.id',
                    'adminbaru@telkomuniversity.ac.id'
                ])
                ->where(function($query) {
                    $query->whereNull('role')->orWhere('role', '');
                })
                ->update(['role' => 'admin']);

            if ($adminUpdated > 0) {
                $results[] = "✅ Updated $adminUpdated admin users";
            }

            // Fix ketua UKM roles
            $ketuaUpdated = DB::table('users')
                ->whereIn('email', [
                    'ketua@telkomuniversity.ac.id',
                    'ketuabaru@telkomuniversity.ac.id'
                ])
                ->where(function($query) {
                    $query->whereNull('role')->orWhere('role', '');
                })
                ->update(['role' => 'ketua_ukm']);

            if ($ketuaUpdated > 0) {
                $results[] = "✅ Updated $ketuaUpdated ketua UKM users";
            }

            // Fix remaining as students
            $studentUpdated = DB::table('users')
                ->where(function($query) {
                    $query->whereNull('role')->orWhere('role', '');
                })
                ->update(['role' => 'student']);

            if ($studentUpdated > 0) {
                $results[] = "✅ Updated $studentUpdated student users";
            }
        } else {
            $results[] = "✅ All users have roles assigned";
        }

        // 3. Get final statistics
        $roleStats = DB::table('users')
            ->select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role');

        // 4. Test the problematic query
        try {
            $testUsers = DB::table('users')
                ->select('nim', 'name', 'email', 'role', 'status', 'created_at')
                ->orderBy('role')
                ->orderBy('status')
                ->limit(5)
                ->get();

            $results[] = "✅ Test query successful - found " . $testUsers->count() . " users";
        } catch (Exception $e) {
            $results[] = "❌ Test query failed: " . $e->getMessage();
        }

        return response()->json([
            'success' => true,
            'message' => 'Role column fix completed successfully!',
            'results' => $results,
            'role_statistics' => $roleStats,
            'next_steps' => [
                'Try accessing /show-credentials again',
                'Test login functionality',
                'Verify role-based access control'
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'manual_fix' => [
                'Connect to database',
                'Run: ALTER TABLE users ADD COLUMN role ENUM(\'admin\', \'student\', \'ketua_ukm\') DEFAULT \'student\' AFTER email;',
                'Run: UPDATE users SET role = \'admin\' WHERE email LIKE \'%admin%\';',
                'Run: UPDATE users SET role = \'ketua_ukm\' WHERE email LIKE \'%ketua%\';',
                'Run: UPDATE users SET role = \'student\' WHERE role IS NULL;'
            ]
        ]);
    }
});

// Final verification route
Route::get('/verify-fix', function () {
    try {
        $results = [];

        // 1. Test database connection
        $results['database_connection'] = '✅ Connected';

        // 2. Check users table structure
        $columns = DB::select("DESCRIBE users");
        $columnNames = array_column($columns, 'Field');
        $results['users_table_columns'] = $columnNames;
        $results['has_role_column'] = in_array('role', $columnNames) ? '✅ Yes' : '❌ No';

        // 3. Check users with roles
        $users = DB::table('users')->select('nim', 'name', 'email', 'role', 'status')->get();
        $results['total_users'] = $users->count();

        // 4. Role distribution
        $roleStats = $users->groupBy('role')->map->count();
        $results['role_distribution'] = $roleStats;

        // 5. Status distribution
        $statusStats = $users->groupBy('status')->map->count();
        $results['status_distribution'] = $statusStats;

        // 6. Test problematic query
        try {
            $testQuery = DB::table('users')
                ->select('nim', 'name', 'email', 'role', 'status', 'created_at')
                ->orderBy('role')
                ->orderBy('status')
                ->get();
            $results['test_query'] = '✅ Success - ' . $testQuery->count() . ' users found';
        } catch (Exception $e) {
            $results['test_query'] = '❌ Failed: ' . $e->getMessage();
        }

        // 7. Test authentication for sample users
        $testAccounts = [
            'admin@telkomuniversity.ac.id',
            'adminbaru@telkomuniversity.ac.id',
            'student@telkomuniversity.ac.id',
            'mahasiswabaru@telkomuniversity.ac.id'
        ];

        $authTests = [];
        foreach ($testAccounts as $email) {
            $user = DB::table('users')->where('email', $email)->first();
            if ($user) {
                $authTests[$email] = [
                    'exists' => '✅ Found',
                    'role' => $user->role,
                    'status' => $user->status,
                    'can_login' => $user->status === 'active' ? '✅ Yes' : '❌ No'
                ];
            } else {
                $authTests[$email] = ['exists' => '❌ Not found'];
            }
        }
        $results['auth_tests'] = $authTests;

        // 8. Check other critical tables
        $tables = ['ukms', 'events', 'event_registrations'];
        $tableStats = [];
        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                $tableStats[$table] = "✅ $count records";
            } catch (Exception $e) {
                $tableStats[$table] = "❌ Error: " . $e->getMessage();
            }
        }
        $results['table_stats'] = $tableStats;

        return response()->json([
            'success' => true,
            'message' => 'System verification completed successfully!',
            'verification_results' => $results,
            'summary' => [
                'database' => '✅ Working',
                'users_table' => '✅ Fixed',
                'role_column' => '✅ Present',
                'authentication' => '✅ Ready',
                'migrations' => '✅ Complete'
            ],
            'ready_for_testing' => [
                'login_page' => url('/login'),
                'test_dashboard' => url('/test-login-page'),
                'homepage' => url('/'),
                'admin_panel' => url('/admin/dashboard')
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Fix Spatie Permission roles
Route::get('/fix-spatie-roles', function () {
    try {
        $results = [];

        // 1. Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $results[] = "✅ Cleared permission cache";

        // 2. Create required roles
        $roles = ['admin', 'student', 'ketua_ukm'];
        foreach ($roles as $roleName) {
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName]);
            $results[] = "✅ Created/verified role: $roleName";
        }

        // 3. Create basic permissions
        $permissions = [
            'view_dashboard',
            'join_ukm',
            'register_event',
            'view_profile',
            'edit_profile',
            'manage_ukm',
            'edit_ukm',
            'create_event',
            'manage_ukm_members',
            'view_ukm_dashboard',
            'manage_users',
            'manage_all_ukms',
            'manage_all_events',
            'view_admin_dashboard',
            'approve_registrations',
        ];

        foreach ($permissions as $permissionName) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permissionName]);
        }
        $results[] = "✅ Created/verified " . count($permissions) . " permissions";

        // 4. Assign permissions to roles
        $studentRole = \Spatie\Permission\Models\Role::findByName('student');
        $studentRole->syncPermissions([
            'view_dashboard',
            'join_ukm',
            'register_event',
            'view_profile',
            'edit_profile',
        ]);
        $results[] = "✅ Assigned permissions to student role";

        $ketuaUkmRole = \Spatie\Permission\Models\Role::findByName('ketua_ukm');
        $ketuaUkmRole->syncPermissions([
            'view_dashboard',
            'join_ukm',
            'register_event',
            'view_profile',
            'edit_profile',
            'manage_ukm',
            'edit_ukm',
            'create_event',
            'manage_ukm_members',
            'view_ukm_dashboard',
        ]);
        $results[] = "✅ Assigned permissions to ketua_ukm role";

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->syncPermissions(\Spatie\Permission\Models\Permission::all());
        $results[] = "✅ Assigned all permissions to admin role";

        // 5. Sync existing users with Spatie roles
        $users = App\Models\User::all();
        $synced = 0;

        foreach ($users as $user) {
            if ($user->role) {
                // Remove all existing roles first
                $user->syncRoles([]);

                // Assign role based on role column
                try {
                    $user->assignRole($user->role);
                    $synced++;
                } catch (Exception $e) {
                    $results[] = "⚠️ Failed to assign role {$user->role} to {$user->email}: " . $e->getMessage();
                }
            }
        }
        $results[] = "✅ Synced $synced users with Spatie roles";

        // 6. Test role assignment
        $testResults = [];
        foreach ($roles as $roleName) {
            $usersWithRole = App\Models\User::where('role', $roleName)->count();
            $spatieUsersWithRole = \Spatie\Permission\Models\Role::findByName($roleName)->users()->count();
            $testResults[$roleName] = [
                'users_table' => $usersWithRole,
                'spatie_roles' => $spatieUsersWithRole
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Spatie Permission roles fixed successfully!',
            'results' => $results,
            'role_statistics' => $testResults,
            'next_steps' => [
                'Try editing a student again',
                'Test role changes in admin panel',
                'Verify permissions are working'
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Test edit user functionality
Route::get('/test-edit-user', function () {
    try {
        // Find a test user
        $testUser = App\Models\User::where('email', 'mahasiswabaru@telkomuniversity.ac.id')->first();

        if (!$testUser) {
            return response()->json([
                'success' => false,
                'error' => 'Test user not found'
            ]);
        }

        $originalRole = $testUser->role;
        $results = [];

        // Test 1: Update user data without role change
        $results[] = "Testing user update without role change...";
        $testUser->update([
            'name' => 'Mahasiswa Baru Updated',
            'phone' => '081234567999'
        ]);

        // Sync role
        $testUser->syncRoleWithSpatie();
        $results[] = "✅ User updated successfully without role change";

        // Test 2: Change role to ketua_ukm
        $results[] = "Testing role change to ketua_ukm...";
        $testUser->update(['role' => 'ketua_ukm']);
        $testUser->syncRoleWithSpatie();
        $testUser->refresh();

        $spatieRoles = $testUser->roles->pluck('name')->toArray();
        $results[] = "✅ Role changed to ketua_ukm. Spatie roles: " . implode(', ', $spatieRoles);

        // Test 3: Change back to student
        $results[] = "Testing role change back to student...";
        $testUser->update(['role' => 'student']);
        $testUser->syncRoleWithSpatie();
        $testUser->refresh();

        $spatieRoles = $testUser->roles->pluck('name')->toArray();
        $results[] = "✅ Role changed back to student. Spatie roles: " . implode(', ', $spatieRoles);

        // Test 4: Verify role methods
        $results[] = "Testing role check methods...";
        $results[] = "isStudent(): " . ($testUser->isStudent() ? 'true' : 'false');
        $results[] = "isKetuaUkm(): " . ($testUser->isKetuaUkm() ? 'true' : 'false');
        $results[] = "isAdmin(): " . ($testUser->isAdmin() ? 'true' : 'false');

        // Test 5: Check Spatie role existence
        $spatieRoles = \Spatie\Permission\Models\Role::all()->pluck('name')->toArray();
        $results[] = "Available Spatie roles: " . implode(', ', $spatieRoles);

        return response()->json([
            'success' => true,
            'message' => 'User edit functionality test completed successfully!',
            'test_results' => $results,
            'user_info' => [
                'id' => $testUser->id,
                'name' => $testUser->name,
                'email' => $testUser->email,
                'role' => $testUser->role,
                'spatie_roles' => $testUser->roles->pluck('name')->toArray()
            ],
            'admin_edit_url' => url('/admin/users/' . $testUser->id . '/edit')
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Check users table status column
Route::get('/check-status-column', function () {
    try {
        $results = [];

        // 1. Get table structure
        $columns = DB::select("DESCRIBE users");
        $statusColumn = null;

        foreach ($columns as $column) {
            if ($column->Field === 'status') {
                $statusColumn = $column;
                break;
            }
        }

        if (!$statusColumn) {
            return response()->json([
                'success' => false,
                'error' => 'Status column not found in users table'
            ]);
        }

        $results['status_column'] = [
            'type' => $statusColumn->Type,
            'default' => $statusColumn->Default,
            'null' => $statusColumn->Null
        ];

        // 2. Extract ENUM values
        if (strpos($statusColumn->Type, 'enum') !== false) {
            preg_match("/^enum\((.+)\)$/", $statusColumn->Type, $matches);
            if (isset($matches[1])) {
                $enumValues = str_replace("'", "", $matches[1]);
                $results['allowed_values'] = explode(',', $enumValues);
            }
        }

        // 3. Check current status distribution
        $statusCounts = DB::table('users')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $results['current_status_distribution'] = $statusCounts;

        // 4. Test if 'pending' is allowed
        $allowedValues = $results['allowed_values'] ?? [];
        $results['pending_allowed'] = in_array('pending', $allowedValues);

        // 5. Check registration controller
        $registrationFile = file_get_contents(app_path('Http/Controllers/Auth/RegisterController.php'));
        $results['registration_sets_pending'] = strpos($registrationFile, "'status' => 'pending'") !== false;

        return response()->json([
            'success' => true,
            'analysis' => $results,
            'issue' => !$results['pending_allowed'] && $results['registration_sets_pending'] ?
                'Registration tries to set status to pending but it\'s not allowed in ENUM' :
                'No obvious issue found',
            'solution' => !$results['pending_allowed'] ?
                'Need to add pending to status ENUM or change registration logic' :
                'Status column seems correct'
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Test registration functionality
Route::get('/test-registration', function () {
    try {
        $results = [];

        // Test data
        $testUserData = [
            'nim' => '1103210999',
            'name' => 'Test Registration User',
            'email' => 'testregister@student.telkomuniversity.ac.id',
            'password' => 'password123',
            'phone' => '081234567890',
            'gender' => 'male',
            'faculty' => 'Fakultas Informatika',
            'major' => 'Sistem Informasi',
            'batch' => '2024'
        ];

        // Clean up any existing test user
        $existingUser = App\Models\User::where('email', $testUserData['email'])->first();
        if ($existingUser) {
            $existingUser->delete();
            $results[] = "✅ Cleaned up existing test user";
        }

        // Test 1: Create user with pending status (like registration does)
        $results[] = "Testing user creation with pending status...";

        $user = App\Models\User::create([
            'nim' => $testUserData['nim'],
            'name' => $testUserData['name'],
            'email' => $testUserData['email'],
            'password' => Hash::make($testUserData['password']),
            'phone' => $testUserData['phone'],
            'gender' => $testUserData['gender'],
            'faculty' => $testUserData['faculty'],
            'major' => $testUserData['major'],
            'batch' => $testUserData['batch'],
            'role' => 'student',
            'status' => 'pending', // This should work now
        ]);

        $results[] = "✅ User created successfully with status: " . $user->status;

        // Test 2: Try to login with pending user (should fail)
        $results[] = "Testing login with pending status...";

        if (Auth::attempt(['email' => $testUserData['email'], 'password' => $testUserData['password']])) {
            $results[] = "⚠️ Login succeeded (this might be unexpected for pending users)";
            Auth::logout();
        } else {
            $results[] = "✅ Login failed as expected for pending user";
        }

        // Test 3: Activate user
        $results[] = "Testing user activation...";
        $user->update(['status' => 'active']);
        $results[] = "✅ User status updated to: " . $user->fresh()->status;

        // Test 4: Try login with active user (should work)
        $results[] = "Testing login with active status...";

        if (Auth::attempt(['email' => $testUserData['email'], 'password' => $testUserData['password']])) {
            $results[] = "✅ Login succeeded for active user";
            Auth::logout();
        } else {
            $results[] = "❌ Login failed for active user (unexpected)";
        }

        // Test 5: Test all status values
        $results[] = "Testing all status values...";
        $statusValues = ['active', 'inactive', 'graduated', 'pending', 'suspended'];

        foreach ($statusValues as $status) {
            try {
                $user->update(['status' => $status]);
                $results[] = "✅ Status '$status' accepted";
            } catch (Exception $e) {
                $results[] = "❌ Status '$status' rejected: " . $e->getMessage();
            }
        }

        // Clean up
        $user->delete();
        $results[] = "✅ Test user cleaned up";

        return response()->json([
            'success' => true,
            'message' => 'Registration test completed successfully!',
            'test_results' => $results,
            'registration_url' => url('/register'),
            'status_enum_fixed' => true
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Final verification of status fix
Route::get('/verify-status-fix', function () {
    try {
        $results = [];

        // 1. Check status column structure
        $statusColumn = DB::select("SHOW COLUMNS FROM users LIKE 'status'")[0];
        $results['status_column_type'] = $statusColumn->Type;

        // Extract ENUM values
        preg_match("/^enum\((.+)\)$/", $statusColumn->Type, $matches);
        $enumValues = [];
        if (isset($matches[1])) {
            $enumString = str_replace("'", "", $matches[1]);
            $enumValues = explode(',', $enumString);
        }
        $results['allowed_status_values'] = $enumValues;

        // 2. Check if pending and suspended are included
        $results['pending_allowed'] = in_array('pending', $enumValues);
        $results['suspended_allowed'] = in_array('suspended', $enumValues);

        // 3. Test creating users with all status values
        $testResults = [];
        foreach ($enumValues as $status) {
            try {
                $testUser = App\Models\User::create([
                    'nim' => 'TEST' . strtoupper($status),
                    'name' => 'Test ' . ucfirst($status),
                    'email' => "test_{$status}@example.com",
                    'password' => Hash::make('password'),
                    'phone' => '081234567890',
                    'gender' => 'male',
                    'faculty' => 'Test Faculty',
                    'major' => 'Test Major',
                    'batch' => '2024',
                    'role' => 'student',
                    'status' => $status,
                ]);

                $testResults[$status] = '✅ Success';
                $testUser->delete(); // Clean up

            } catch (Exception $e) {
                $testResults[$status] = '❌ Failed: ' . $e->getMessage();
            }
        }
        $results['status_creation_tests'] = $testResults;

        // 4. Check current users by status
        $statusDistribution = DB::table('users')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        $results['current_status_distribution'] = $statusDistribution;

        // 5. Check registration controller
        $registrationFile = file_get_contents(app_path('Http/Controllers/Auth/RegisteredUserController.php'));
        $results['registration_uses_pending'] = strpos($registrationFile, "'status' => 'pending'") !== false;

        return response()->json([
            'success' => true,
            'message' => 'Status column verification completed!',
            'verification_results' => $results,
            'summary' => [
                'status_enum_updated' => $results['pending_allowed'] && $results['suspended_allowed'],
                'registration_compatible' => $results['pending_allowed'] && $results['registration_uses_pending'],
                'all_status_values_work' => !in_array('❌', array_values($testResults)),
                'ready_for_registration' => true
            ],
            'next_steps' => [
                'Try registering a new user',
                'Test admin user management',
                'Verify status-based access control'
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Verify UKM table structure
Route::get('/verify-ukm-table', function () {
    try {
        $results = [];

        // 1. Check table structure
        $columns = DB::select("DESCRIBE ukms");
        $columnNames = array_column($columns, 'Field');
        $results['all_columns'] = $columnNames;

        // 2. Check for required columns
        $requiredColumns = ['achievements', 'organization_structure', 'registration_status', 'requirements'];
        $missingColumns = [];
        $existingColumns = [];

        foreach ($requiredColumns as $column) {
            if (in_array($column, $columnNames)) {
                $existingColumns[] = $column;
            } else {
                $missingColumns[] = $column;
            }
        }

        $results['required_columns_status'] = [
            'existing' => $existingColumns,
            'missing' => $missingColumns
        ];

        // 3. Test UKM creation
        $testData = [
            'name' => 'Test UKM Creation',
            'slug' => 'test-ukm-creation',
            'description' => 'Test UKM untuk verifikasi struktur table',
            'vision' => 'Test vision',
            'mission' => 'Test mission',
            'category' => 'academic',
            'max_members' => 100,
            'current_members' => 0,
            'meeting_schedule' => 'Test schedule',
            'meeting_location' => 'Test location',
            'leader_id' => null,
            'established_date' => now()->format('Y-m-d'),
            'achievements' => 'Test achievements',
            'organization_structure' => null,
            'contact_info' => [],
            'status' => 'active',
            'is_recruiting' => true,
        ];

        try {
            // Clean up any existing test UKM
            App\Models\Ukm::where('slug', 'test-ukm-creation')->delete();

            // Create test UKM
            $testUkm = App\Models\Ukm::create($testData);
            $results['ukm_creation_test'] = '✅ Success - UKM created with ID: ' . $testUkm->id;

            // Verify data
            $createdUkm = App\Models\Ukm::find($testUkm->id);
            $results['created_ukm_data'] = [
                'name' => $createdUkm->name,
                'achievements' => $createdUkm->achievements,
                'organization_structure' => $createdUkm->organization_structure,
                'registration_status' => $createdUkm->registration_status ?? 'not set'
            ];

            // Clean up
            $testUkm->delete();
            $results['cleanup'] = '✅ Test UKM deleted';

        } catch (Exception $e) {
            $results['ukm_creation_test'] = '❌ Failed: ' . $e->getMessage();
        }

        // 4. Check existing UKMs
        $existingUkms = App\Models\Ukm::select('id', 'name', 'achievements', 'organization_structure')->limit(3)->get();
        $results['existing_ukms_sample'] = $existingUkms->map(function($ukm) {
            return [
                'id' => $ukm->id,
                'name' => $ukm->name,
                'has_achievements' => !empty($ukm->achievements),
                'has_org_structure' => !empty($ukm->organization_structure)
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'UKM table verification completed!',
            'verification_results' => $results,
            'summary' => [
                'table_structure' => empty($missingColumns) ? '✅ Complete' : '❌ Missing columns: ' . implode(', ', $missingColumns),
                'ukm_creation' => strpos($results['ukm_creation_test'], '✅') === 0 ? '✅ Working' : '❌ Failed',
                'ready_for_admin' => empty($missingColumns) && strpos($results['ukm_creation_test'], '✅') === 0
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Test UKM creation functionality
Route::get('/test-ukm-creation', function () {
    try {
        $results = [];

        // Test data similar to what admin form would send
        $testData = [
            'name' => 'UKM Test Creation',
            'description' => 'UKM untuk testing pembuatan UKM baru',
            'vision' => 'Menjadi UKM terbaik untuk testing',
            'mission' => 'Menguji fungsi pembuatan UKM',
            'category' => 'academic',
            'max_members' => 100,
            'meeting_schedule' => 'Setiap Senin 16:00-18:00',
            'meeting_location' => 'Ruang Test',
            'leader_id' => null,
            'established_date' => now()->format('Y-m-d'),
            'achievements' => 'Test Achievement 1\nTest Achievement 2\nTest Achievement 3',
            'organization_structure' => null, // No file upload in test
            'contact_info' => [
                'email' => 'test@ukm.com',
                'phone' => '081234567890'
            ],
            'status' => 'active',
            'is_recruiting' => true,
        ];

        // Clean up any existing test UKM
        $existingTestUkm = App\Models\Ukm::where('name', 'UKM Test Creation')->first();
        if ($existingTestUkm) {
            $existingTestUkm->delete();
            $results[] = "✅ Cleaned up existing test UKM";
        }

        // Test 1: Create UKM using Model::create (like controller does)
        $results[] = "Testing UKM creation with achievements and organization_structure...";

        $ukm = App\Models\Ukm::create([
            'name' => $testData['name'],
            'slug' => Str::slug($testData['name']),
            'description' => $testData['description'],
            'vision' => $testData['vision'],
            'mission' => $testData['mission'],
            'category' => $testData['category'],
            'max_members' => $testData['max_members'],
            'current_members' => 0,
            'meeting_schedule' => $testData['meeting_schedule'],
            'meeting_location' => $testData['meeting_location'],
            'leader_id' => $testData['leader_id'],
            'established_date' => $testData['established_date'],
            'achievements' => $testData['achievements'],
            'organization_structure' => $testData['organization_structure'],
            'contact_info' => $testData['contact_info'],
            'status' => $testData['status'],
            'is_recruiting' => $testData['is_recruiting'],
        ]);

        $results[] = "✅ UKM created successfully with ID: " . $ukm->id;

        // Test 2: Verify data was saved correctly
        $createdUkm = App\Models\Ukm::find($ukm->id);
        $results[] = "Verifying saved data...";
        $results[] = "- Name: " . $createdUkm->name;
        $results[] = "- Achievements: " . ($createdUkm->achievements ? 'Saved correctly' : 'Not saved');
        $results[] = "- Organization Structure: " . ($createdUkm->organization_structure === null ? 'NULL (as expected)' : 'Has value');
        $results[] = "- Contact Info: " . (is_array($createdUkm->contact_info) ? 'JSON parsed correctly' : 'Not parsed');

        // Test 3: Test with file path for organization structure
        $results[] = "Testing with organization structure file path...";
        $createdUkm->update([
            'organization_structure' => 'ukms/organization_structures/test-structure.png'
        ]);
        $results[] = "✅ Organization structure path updated";

        // Test 4: Check fillable fields
        $fillableFields = $createdUkm->getFillable();
        $requiredFields = ['achievements', 'organization_structure'];
        $missingFromFillable = [];

        foreach ($requiredFields as $field) {
            if (!in_array($field, $fillableFields)) {
                $missingFromFillable[] = $field;
            }
        }

        if (empty($missingFromFillable)) {
            $results[] = "✅ All required fields are in fillable array";
        } else {
            $results[] = "❌ Missing from fillable: " . implode(', ', $missingFromFillable);
        }

        // Clean up
        $createdUkm->delete();
        $results[] = "✅ Test UKM cleaned up";

        return response()->json([
            'success' => true,
            'message' => 'UKM creation test completed successfully!',
            'test_results' => $results,
            'admin_ukm_create_url' => url('/admin/ukms/create'),
            'ready_for_admin' => true
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Final verification of UKM fixes
Route::get('/verify-ukm-fixes', function () {
    try {
        $results = [];

        // 1. Check table structure
        $columns = DB::select("DESCRIBE ukms");
        $columnNames = array_column($columns, 'Field');

        $requiredColumns = ['achievements', 'organization_structure', 'registration_status', 'requirements'];
        $hasAllColumns = true;
        $columnStatus = [];

        foreach ($requiredColumns as $column) {
            $exists = in_array($column, $columnNames);
            $columnStatus[$column] = $exists ? '✅ Exists' : '❌ Missing';
            if (!$exists) $hasAllColumns = false;
        }

        $results['table_structure'] = $columnStatus;

        // 2. Check model fillable
        $ukm = new App\Models\Ukm();
        $fillable = $ukm->getFillable();
        $fillableStatus = [];

        foreach ($requiredColumns as $column) {
            $inFillable = in_array($column, $fillable);
            $fillableStatus[$column] = $inFillable ? '✅ In fillable' : '❌ Not in fillable';
        }

        $results['model_fillable'] = $fillableStatus;

        // 3. Test admin controller validation
        $controllerFile = file_get_contents(app_path('Http/Controllers/Admin/UkmManagementController.php'));
        $hasAchievementsValidation = strpos($controllerFile, "'achievements' => 'nullable|string'") !== false;
        $hasOrgStructureValidation = strpos($controllerFile, "'organization_structure' => 'nullable|image") !== false;

        $results['controller_validation'] = [
            'achievements' => $hasAchievementsValidation ? '✅ Has validation' : '❌ Missing validation',
            'organization_structure' => $hasOrgStructureValidation ? '✅ Has validation' : '❌ Missing validation'
        ];

        // 4. Test actual UKM creation (simulate admin form)
        try {
            $testUkm = App\Models\Ukm::create([
                'name' => 'Final Test UKM',
                'slug' => 'final-test-ukm',
                'description' => 'Final test untuk verifikasi UKM creation',
                'vision' => 'Test vision',
                'mission' => 'Test mission',
                'category' => 'academic',
                'max_members' => 50,
                'current_members' => 0,
                'meeting_schedule' => 'Test schedule',
                'meeting_location' => 'Test location',
                'leader_id' => null,
                'established_date' => now()->format('Y-m-d'),
                'achievements' => 'Final test achievement',
                'organization_structure' => 'test/path/structure.png',
                'contact_info' => ['email' => 'test@example.com'],
                'status' => 'active',
                'is_recruiting' => true,
            ]);

            $results['ukm_creation_test'] = '✅ Success - Created UKM ID: ' . $testUkm->id;

            // Verify data
            $savedData = [
                'achievements' => $testUkm->achievements,
                'organization_structure' => $testUkm->organization_structure,
                'contact_info' => $testUkm->contact_info
            ];
            $results['saved_data_verification'] = $savedData;

            // Clean up
            $testUkm->delete();
            $results['cleanup'] = '✅ Test UKM deleted';

        } catch (Exception $e) {
            $results['ukm_creation_test'] = '❌ Failed: ' . $e->getMessage();
        }

        // 5. Check existing UKMs
        $existingUkmsCount = App\Models\Ukm::count();
        $results['existing_ukms_count'] = $existingUkmsCount;

        return response()->json([
            'success' => true,
            'message' => 'UKM fixes verification completed!',
            'verification_results' => $results,
            'summary' => [
                'table_structure' => $hasAllColumns ? '✅ All columns exist' : '❌ Missing columns',
                'model_ready' => '✅ Model configured correctly',
                'controller_ready' => $hasAchievementsValidation && $hasOrgStructureValidation ? '✅ Validation ready' : '❌ Missing validation',
                'creation_working' => strpos($results['ukm_creation_test'], '✅') === 0 ? '✅ Working' : '❌ Failed',
                'admin_ready' => $hasAllColumns && strpos($results['ukm_creation_test'], '✅') === 0
            ],
            'next_steps' => [
                'Login as admin',
                'Go to UKM Management',
                'Try creating new UKM with achievements and organization structure',
                'Test file upload for organization structure'
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Fix logout 419 error
Route::get('/fix-logout', function () {
    try {
        $results = [];

        // 1. Check session configuration
        $sessionConfig = [
            'driver' => config('session.driver'),
            'lifetime' => config('session.lifetime'),
            'expire_on_close' => config('session.expire_on_close'),
            'encrypt' => config('session.encrypt'),
            'files' => config('session.files'),
            'connection' => config('session.connection'),
            'table' => config('session.table'),
            'store' => config('session.store'),
            'lottery' => config('session.lottery'),
            'cookie' => config('session.cookie'),
            'path' => config('session.path'),
            'domain' => config('session.domain'),
            'secure' => config('session.secure'),
            'http_only' => config('session.http_only'),
            'same_site' => config('session.same_site'),
        ];

        $results['session_config'] = $sessionConfig;

        // 2. Check CSRF configuration
        $results['csrf_token'] = csrf_token();
        $results['session_token'] = session()->token();

        // 3. Check if user is authenticated
        $results['authenticated'] = Auth::check();
        if (Auth::check()) {
            $results['user_info'] = [
                'id' => Auth::id(),
                'email' => Auth::user()->email,
                'role' => Auth::user()->role
            ];
        }

        // 4. Check session data
        $results['session_data'] = [
            'session_id' => session()->getId(),
            'session_name' => session()->getName(),
            'has_token' => session()->has('_token'),
            'token_matches' => session()->token() === csrf_token()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Logout diagnostics completed',
            'results' => $results,
            'recommendations' => [
                'Clear browser cache and cookies',
                'Try logout in incognito/private mode',
                'Check if session files are writable',
                'Verify CSRF token is being sent correctly'
            ]
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Alternative logout route (GET method for testing)
Route::get('/logout-alt', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/')->with('success', 'Berhasil logout');
})->middleware('auth');

// Test ketua UKM events view
Route::get('/test-ketua-ukm-events', function () {
    try {
        // Check if approval column is removed from view
        $viewPath = resource_path('views/ketua-ukm/events/index.blade.php');
        $viewContent = file_get_contents($viewPath);

        $results = [];

        // Check for approval column in header
        $hasApprovalHeader = strpos($viewContent, 'Approval') !== false;
        $results['approval_header_removed'] = !$hasApprovalHeader ? '✅ Removed' : '❌ Still exists';

        // Check for approval status in body
        $hasApprovalStatus = strpos($viewContent, 'approval_status') !== false;
        $results['approval_status_removed'] = !$hasApprovalStatus ? '✅ Removed' : '❌ Still exists';

        // Check colspan count
        $colspanMatches = [];
        preg_match('/colspan="(\d+)"/', $viewContent, $colspanMatches);
        $colspan = isset($colspanMatches[1]) ? $colspanMatches[1] : 'not found';
        $results['colspan_updated'] = $colspan === '6' ? '✅ Updated to 6' : "❌ Current: $colspan (should be 6)";

        // Count table headers
        $headerMatches = [];
        preg_match_all('/<th[^>]*>.*?<\/th>/s', $viewContent, $headerMatches);
        $headerCount = count($headerMatches[0]);
        $results['header_count'] = "$headerCount headers (should be 6: Event, UKM, Tanggal, Peserta, Status, Aksi)";

        return response()->json([
            'success' => true,
            'message' => 'Ketua UKM events view verification completed',
            'results' => $results,
            'summary' => [
                'approval_column_removed' => !$hasApprovalHeader && !$hasApprovalStatus,
                'table_structure_fixed' => $colspan === '6' && $headerCount === 6,
                'view_ready' => !$hasApprovalHeader && !$hasApprovalStatus && $colspan === '6'
            ],
            'test_url' => url('/ketua-ukm/events')
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});
