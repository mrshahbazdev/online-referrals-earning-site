@extends('admin.layouts.app')

@section('title', 'Admin Management')

@push('styles')
<style>
    /* Is page ke makhsoos styles yahan add karein */
    .table-container { background-color: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 600px; }
    th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
    th { background-color: #334155; font-weight: 600; }
    td { color: var(--text-secondary); }
    tr:last-child td { border-bottom: none; }
    .action-btn { background: none; border: none; cursor: pointer; padding: 0.5rem; border-radius: 6px; transition: background-color 0.2s; }
    .action-btn:hover { background-color: #475569; }
    .action-btn i { font-size: 1.25rem; }
    .pagination-links { margin-top: 1.5rem; color: var(--text-secondary); }
    .add-btn { background-color: var(--accent-color); color: var(--bg-dark); border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; }
    .alert { padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid; }
    .alert-success { background-color: #166534; border-color: var(--green); color: #dcfce7; }
    .alert-danger { background-color: #991b1b; border-color: var(--red); color: #fee2e2; }
    .alert-danger ul { list-style-position: inside; }

    /* Modal Styles */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; visibility: hidden; transition: opacity 0.3s, visibility 0.3s; }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-content { background-color: var(--card-bg); padding: 2rem; border-radius: 12px; width: 90%; max-width: 500px; transform: scale(0.95); transition: transform 0.3s; }
    .modal-overlay.active .modal-content { transform: scale(1); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .modal-header h2 { font-size: 1.25rem; }
    .close-modal-btn { background: none; border: none; color: var(--text-secondary); font-size: 1.5rem; cursor: pointer; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
    .form-group input { width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: #334155; color: var(--text-primary); }
    .modal-footer { margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; }
    .modal-footer button { padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; }
    .btn-secondary { background-color: #475569; color: var(--text-primary); }
    .btn-primary { background-color: var(--accent-color); color: var(--bg-dark); }
    .btn-danger { background-color: var(--red); color: var(--text-primary); }

    @media (max-width: 768px) {
        .table-container { border: none; background-color: transparent; }
        table thead { display: none; }
        table tr { display: block; margin-bottom: 1rem; border-radius: 12px; border: 1px solid var(--border-color); background-color: var(--card-bg); }
        table td { display: flex; justify-content: space-between; align-items: center; text-align: right; padding: 0.75rem 1rem; border-bottom: 1px solid var(--border-color); }
        table td:last-child { border-bottom: none; }
        table td::before { content: attr(data-label); font-weight: 600; text-align: left; margin-right: 1rem; color: var(--text-primary); }
    }
</style>
@endpush

@section('content')
    <header class="main-header">
        <button class="mobile-nav-toggle" id="mobileNavToggle"><i class="ph ph-list"></i></button>
        <h1>Admin Management</h1>
        <button class="add-btn" id="addAdminBtn"><i class="ph ph-plus"></i> Add New Admin</button>
    </header>

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
                    <th>Joined At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($admins as $admin)
                    <tr>
                        <td data-label="ID">{{ $admin->id }}</td>
                        <td data-label="Username">{{ $admin->username }}</td>
                        <td data-label="Email">{{ $admin->email }}</td>
                        <td data-label="Joined At">{{ $admin->created_at->format('d M, Y') }}</td>
                        <td data-label="Actions">
                            <button class="action-btn delete-btn" title="Delete Admin" data-id="{{ $admin->id }}"><i class="ph ph-trash" style="color: var(--red);"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">No other admins found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links">
        {{ $admins->links() }}
    </div>

    <!-- Add Admin Modal -->
    <div class="modal-overlay" id="adminModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Admin</h2>
                <button class="close-modal-btn">&times;</button>
            </div>
            <form id="adminForm" method="POST" action="{{ route('admin.admins.store') }}">
                @csrf
                <div class="form-group"><label for="username">Username</label><input type="text" id="username" name="username" required></div>
                <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" required></div>
                <div class="form-group"><label for="password">Password</label><input type="password" id="password" name="password" required></div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-primary">Save Admin</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header"><h2>Confirm Deletion</h2><button class="close-modal-btn">&times;</button></div>
            <p>Are you sure you want to delete this admin? This action cannot be undone.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-danger">Delete Admin</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const adminModal = document.getElementById('adminModal');
    const deleteModal = document.getElementById('deleteModal');
    const addAdminBtn = document.getElementById('addAdminBtn');
    const closeButtons = document.querySelectorAll('.close-modal-btn');
    const adminForm = document.getElementById('adminForm');
    const deleteForm = document.getElementById('deleteForm');

    // Open Add Admin Modal
    addAdminBtn.addEventListener('click', () => {
        adminForm.reset();
        adminModal.classList.add('active');
    });

    // Open Delete Confirmation Modal
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', () => {
            const adminId = button.dataset.id;
            deleteForm.action = `/admin/admins/${adminId}`;
            deleteModal.classList.add('active');
        });
    });

    // Close Modals
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            adminModal.classList.remove('active');
            deleteModal.classList.remove('active');
        });
    });
});
    </script>
    @endpush
