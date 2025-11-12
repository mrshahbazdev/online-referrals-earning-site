@extends('admin.layouts.app')

@section('title', 'Users Management')

@push('styles')
<style>
        :root {
            --bg-dark: #111827; --sidebar-bg: #1E293B; --card-bg: #1E293B;
            --text-primary: #f1f5f9; --text-secondary: #94a3b8; --accent-color: #facc15;
            --border-color: #334155; --green: #22c55e; --red: #ef4444; --blue: #3b82f6;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-dark); color: var(--text-primary); }
        .dashboard-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background-color: var(--sidebar-bg); padding: 1.5rem; display: flex; flex-direction: column; border-right: 1px solid var(--border-color); }
        .sidebar-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2.5rem; }
        .sidebar-header .icon { font-size: 2rem; color: var(--accent-color); }
        .sidebar-header h2 { font-size: 1.25rem; font-weight: 700; }
        .sidebar-nav { flex-grow: 1; }
        .sidebar-nav ul { list-style: none; }
        .sidebar-nav a { display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-radius: 8px; text-decoration: none; color: var(--text-secondary); font-weight: 500; transition: background-color 0.2s, color 0.2s; }
        .sidebar-nav a:hover { background-color: #334155; color: var(--text-primary); }
        .sidebar-nav a.active { background-color: var(--accent-color); color: var(--bg-dark); font-weight: 600; }
        .sidebar-nav a i { font-size: 1.25rem; }
        .logout-form button { width: 100%; background: none; border: none; cursor: pointer; text-align: left; }
        .logout-link { color: var(--text-secondary) !important; }
        .logout-link:hover { color: var(--text-primary) !important; }
        .main-content { flex-grow: 1; padding: 2rem; overflow-y: auto; }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .main-header h1 { font-size: 1.75rem; }
        .add-user-btn { background-color: var(--accent-color); color: var(--bg-dark); border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; }
        .table-container { background-color: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { background-color: #334155; font-weight: 600; }
        td { color: var(--text-secondary); }
        tr:last-child td { border-bottom: none; }
        .action-btn { background: none; border: none; cursor: pointer; padding: 0.5rem; border-radius: 6px; transition: background-color 0.2s; }
        .action-btn:hover { background-color: #475569; }
        .action-btn i { font-size: 1.25rem; }
        .pagination-links { margin-top: 1.5rem; color: var(--text-secondary); }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; visibility: hidden; transition: opacity 0.3s, visibility 0.3s; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content { background-color: var(--card-bg); padding: 2rem; border-radius: 12px; width: 90%; max-width: 500px; transform: scale(0.95); transition: transform 0.3s; }
        .modal-overlay.active .modal-content { transform: scale(1); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .modal-header h2 { font-size: 1.25rem; }
        .close-modal-btn { background: none; border: none; color: var(--text-secondary); font-size: 1.5rem; cursor: pointer; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-group input, .form-group select { width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-input); color: var(--text-primary); }
        .modal-footer { margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; }
        .modal-footer button { padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; }
        .btn-secondary { background-color: #475569; color: var(--text-primary); }
        .btn-primary { background-color: var(--accent-color); color: var(--bg-dark); }
        .btn-danger { background-color: var(--red); color: var(--text-primary); }
        .alert { padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid; }
        .alert-success { background-color: #166534; border-color: var(--green); color: #dcfce7; }
        .alert-danger { background-color: #991b1b; border-color: var(--red); color: #fee2e2; }
        .alert-danger ul { list-style-position: inside; }
        .pagination-links { margin-top: 1.5rem; }
        /* New Pagination Styles */
        .pagination-links nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pagination-links a, .pagination-links span {
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .pagination-links a {
            background-color: var(--accent-color);
            color: var(--bg-dark);
            transition: background-color 0.2s;
        }
        .pagination-links a:hover {
            background-color: #fde047; /* Lighter yellow */
        }
        /* This styles the disabled "Previous" or "Next" button */
        .pagination-links span {
            background-color: var(--border-color);
            color: var(--text-secondary);
            cursor: not-allowed;
        }
        .modal-content-full {
        background-color: var(--card-bg);
        border-radius: 12px;
        width: 95%;
        max-width: 1000px; /* Bari screens ke liye zyada width */
        height: 90vh; /* Viewport ka 90% height */
        display: flex;
        flex-direction: column;
        transform: scale(0.95);
        transition: transform 0.3s;
    }
    .modal-overlay.active .modal-content-full {
        transform: scale(1);
    }
    .modal-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        flex-shrink: 0;
    }
    .modal-body {
        padding: 1.5rem;
        overflow-y: auto; /* Body ko scrollable banayein */
        flex-grow: 1;
    }
    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-color);
        flex-shrink: 0;
    }
    .table-header-sortable a {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        color: inherit;
    }
    </style>

@endpush
@section('content')
        <header class="main-header">
            <button class="mobile-nav-toggle" id="mobileNavToggle"><i class="ph ph-list"></i></button>
            <h1>User Management</h1>
            <button class="add-btn" id="addUserBtn"><i class="ph ph-plus"></i> Add New User</button>
        </header>

        <!-- Search and Filter Form -->
        <div class="mb-4 bg-[#1E1F2B] p-4 rounded-lg">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="flex items-center gap-4">
                    <input type="text" name="search" placeholder="Search by username or email..." value="{{ request('search') }}" class="w-full bg-[#334155] border-gray-700 rounded-lg p-2">
                    <button type="submit" class="bg-yellow-400 text-black font-bold py-2 px-4 rounded-lg">Search</button>
                </div>
            </form>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th class="table-header-sortable">
                            <a href="{{ route('admin.users.index', ['sort_by' => 'balance', 'sort_direction' => request('sort_by') == 'balance' && request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">
                                Balance
                                @if(request('sort_by') == 'balance') <i class="ph ph-sort-ascending"></i> @endif
                            </a>
                        </th>
                        <th class="table-header-sortable">
                            <a href="{{ route('admin.users.index', ['sort_by' => 'level', 'sort_direction' => request('sort_by') == 'level' && request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">
                                Level
                                @if(request('sort_by') == 'level') <i class="ph ph-sort-ascending"></i> @endif
                            </a>
                        </th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td data-label="ID">{{ $user->id }}</td>
                            <td data-label="Username">{{ $user->username }}</td>
                            <td data-label="Email">{{ $user->email }}</td>
                            <td data-label="Balance">${{ number_format($user->balance, 2) }}</td>
                            <td data-label="Level">{{ $user->level->name ?? 'N/A' }}</td>
                            <td data-label="Joined">{{ $user->created_at->format('d M, Y') }}</td>
                            <td data-label="Actions">
                                <button class="action-btn edit-btn" data-user='@json($user)'><i class="ph ph-pencil-simple" style="color: var(--accent-color);"></i></button>
                                <button class="action-btn delete-btn" data-id="{{ $user->id }}"><i class="ph ph-trash" style="color: var(--red);"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align: center; padding: 2rem;">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

            <div class="pagination-links">
                {{ $users->links() }}
            </div>
        </main>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal-overlay" id="userModal">
            <div class="modal-content-full">
                <div class="modal-header">
                    <h2 id="modalTitle">Edit User Details</h2>
                    <button class="close-modal-btn">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="userForm" method="POST">
                        @csrf
                        <div id="methodField">@method('PUT')</div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Column 1: Account Details -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-yellow-400 border-b border-gray-700 pb-2">Account Details</h3>
                                <div class="form-group"><label for="username">Username</label><input type="text" id="username" name="username" required class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3"></div>
                                <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" required class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3"></div>
                                <div class="form-group"><label for="password">New Password (leave blank to keep unchanged)</label><input type="password" id="password" name="password" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3"></div>
                            </div>

                            <!-- Column 2: Personal Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-yellow-400 border-b border-gray-700 pb-2">Personal Information</h3>
                                <div class="form-group"><label for="first_name">First Name</label><input type="text" id="first_name" name="first_name" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3"></div>
                                <div class="form-group"><label for="last_name">Last Name</label><input type="text" id="last_name" name="last_name" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3"></div>
                                <div class="form-group"><label for="mobile_number">Mobile Number</label><input type="text" id="mobile_number" name="mobile_number" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3"></div>
                                <div class="form-group"><label for="address">Address</label><input type="text" id="address" name="address" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3"></div>
                            </div>

                            <!-- Column 3: Application Data -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-yellow-400 border-b border-gray-700 pb-2">Application Data</h3>
                                <div class="form-group"><label for="balance">Balance</label><input type="number" step="0.01" id="balance" name="balance" required class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3"></div>
                                <div class="form-group">
                                    <label for="level_id">Level</label>
                                    <select id="level_id" name="level_id" required class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3">
                                        @foreach($levels as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kyc_status">KYC Status</label>
                                    <select id="kyc_status" name="kyc_status" required class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3">
                                        <option value="unverified">Unverified</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="text-sm font-medium text-gray-400">Referral Info</label>
                                    <div class="mt-1 bg-[#334155] border border-gray-700 rounded-lg p-3 text-sm">
                                        <p>Referral Code: <span id="referral_code" class="font-bold"></span></p>
                                        <p>Referred By: <span id="referred_by" class="font-bold"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                            <button type="submit" class="btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header"><h2>Confirm Deletion</h2><button class="close-modal-btn">&times;</button></div>
            <p>Are you sure you want to delete this user? This action cannot be undone.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-danger">Delete User</button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userModal = document.getElementById('userModal');
            const deleteModal = document.getElementById('deleteModal');
            const addUserBtn = document.getElementById('addUserBtn');
            const closeButtons = document.querySelectorAll('.close-modal-btn');
            const userForm = document.getElementById('userForm');
            const deleteForm = document.getElementById('deleteForm');
            const modalTitle = document.getElementById('modalTitle');
            const methodField = document.getElementById('methodField');

            // Open Add User Modal
            addUserBtn.addEventListener('click', () => {
                userForm.reset();
                userForm.action = "{{ route('admin.users.store') }}";
                methodField.innerHTML = ''; // No method spoofing for POST
                modalTitle.textContent = 'Add New User';
                userModal.classList.add('active');
            });

            // Open Edit User Modal
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const user = JSON.parse(button.dataset.user);
                    userForm.reset();
                    userForm.action = `/admin/users/${user.id}`;
                    methodField.innerHTML = '@method("PUT")';
                    modalTitle.textContent = 'Edit User Details';

                    // Tamam fields ko populate karein
                    document.getElementById('username').value = user.username;
                    document.getElementById('email').value = user.email;
                    document.getElementById('first_name').value = user.first_name || '';
                    document.getElementById('last_name').value = user.last_name || '';
                    document.getElementById('mobile_number').value = user.mobile_number || '';
                    document.getElementById('address').value = user.address || '';
                    document.getElementById('balance').value = user.balance;
                    document.getElementById('level_id').value = user.level_id;
                    document.getElementById('kyc_status').value = user.kyc_status;
                    document.getElementById('referral_code').textContent = user.referral_code || 'N/A';
                    document.getElementById('referred_by').textContent = user.referrer ? user.referrer.username : 'None';

                    userModal.classList.add('active');
                });
            });

            // Open Delete Confirmation Modal
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const userId = button.dataset.id;
                    deleteForm.action = `/admin/users/${userId}`;
                    deleteModal.classList.add('active');
                });
            });

            // Close Modals
            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    userModal.classList.remove('active');
                    deleteModal.classList.remove('active');
                });
            });
        });
    </script>
    @endpush
@endsection
