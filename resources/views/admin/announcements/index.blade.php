@extends('admin.layouts.app')

@section('title', 'Announcements Management')

@push('styles')
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --bg-dark: #111827; --sidebar-bg: #1E293B; --card-bg: #1E293B;
            --text-primary: #f1f5f9; --text-secondary: #94a3b8; --accent-color: #facc15;
            --border-color: #334155; --green: #22c55e; --red: #ef4444; --blue: #3b82f6;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-dark); color: var(--text-primary); }
        .dashboard-layout { display: flex; min-height: 100vh; position: relative; }
        .sidebar { width: 260px; background-color: var(--sidebar-bg); padding: 1.5rem; display: flex; flex-direction: column; border-right: 1px solid var(--border-color); transition: transform 0.3s ease-in-out; }
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
        .mobile-nav-toggle { display: none; background: none; border: none; color: var(--text-primary); font-size: 1.5rem; cursor: pointer; }
        .add-btn { background-color: var(--accent-color); color: var(--bg-dark); border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; }
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
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; visibility: hidden; transition: opacity 0.3s, visibility 0.3s; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content { background-color: var(--card-bg); padding: 2rem; border-radius: 12px; width: 90%; max-width: 600px; transform: scale(0.95); transition: transform 0.3s; }
        .modal-overlay.active .modal-content { transform: scale(1); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .modal-header h2 { font-size: 1.25rem; }
        .close-modal-btn { background: none; border: none; color: var(--text-secondary); font-size: 1.5rem; cursor: pointer; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: #334155; color: var(--text-primary); }
        .form-group textarea { min-height: 120px; }
        .modal-footer { margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; }
        .modal-footer button { padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; }
        .btn-secondary { background-color: #475569; color: var(--text-primary); }
        .btn-primary { background-color: var(--accent-color); color: var(--bg-dark); }
        .btn-danger { background-color: var(--red); color: var(--text-primary); }
        .alert { padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid; }
        .alert-success { background-color: #166534; border-color: var(--green); color: #dcfce7; }
        .alert-danger { background-color: #991b1b; border-color: var(--red); color: #fee2e2; }
        .alert-danger ul { list-style-position: inside; }
        .mobile-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99; }

        @media (max-width: 768px) {
            .sidebar { position: fixed; left: 0; top: 0; height: 100%; transform: translateX(-100%); z-index: 100; }
            .sidebar.active { transform: translateX(0); }
            .mobile-nav-toggle { display: block; }
            .mobile-overlay.active { display: block; }
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
        <main class="main-content">
            <header class="main-header">
                <button class="mobile-nav-toggle" id="mobileNavToggle"><i class="ph ph-list"></i></button>
                <h1>Announcements</h1>
                <button class="add-btn" id="addAnnouncementBtn"><i class="ph ph-plus"></i> Add New Announcement</button>
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
                        <th>Title</th>
                        <th>Content</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                        <tr>
                            <td data-label="Title">{{ $announcement->title }}</td>
                            <td data-label="Content">{{ Str::limit($announcement->content, 50) }}</td>
                            <td data-label="Status">
                                @if($announcement->is_active)
                                    <span class="status-badge status-approved">Active</span>
                                @else
                                    <span class="status-badge status-rejected">Inactive</span>
                                @endif
                            </td>
                            <td data-label="Created At">{{ $announcement->created_at->format('d M, Y') }}</td>
                            <td data-label="Actions">
                                <div class="flex items-center gap-2">
                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('admin.announcements.toggle', $announcement) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-btn" title="{{ $announcement->is_active ? 'Deactivate' : 'Activate' }}">
                                            @if($announcement->is_active)
                                                <i class="ph ph-toggle-right text-3xl text-green-400"></i>
                                            @else
                                                <i class="ph ph-toggle-left text-3xl text-gray-500"></i>
                                            @endif
                                        </button>
                                    </form>

                                    <button class="action-btn edit-btn" data-announcement='@json($announcement)'><i class="ph ph-pencil-simple" style="color: var(--accent-color);"></i></button>
                                    <button class="action-btn delete-btn" data-id="{{ $announcement->id }}"><i class="ph ph-trash" style="color: var(--red);"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center;">No announcements found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

            <div class="pagination-links">{{ $announcements->links() }}</div>
        </main>
    </div>

    <!-- Add/Edit Announcement Modal -->
    <div class="modal-overlay" id="announcementModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Announcement</h2>
                <button class="close-modal-btn">&times;</button>
            </div>
            <form id="announcementForm" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="form-group"><label for="title">Title</label><input type="text" id="title" name="title" required></div>
                <div class="form-group"><label for="content">Content</label><textarea id="content" name="content" required></textarea></div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-primary">Save Announcement</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header"><h2>Confirm Deletion</h2><button class="close-modal-btn">&times;</button></div>
            <p>Are you sure you want to delete this announcement?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-danger">Delete Announcement</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile navigation toggle
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');

            mobileNavToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                mobileOverlay.classList.toggle('active');
            });

            mobileOverlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                mobileOverlay.classList.remove('active');
            });

            // Modal JavaScript
            const announcementModal = document.getElementById('announcementModal');
            const deleteModal = document.getElementById('deleteModal');
            const addAnnouncementBtn = document.getElementById('addAnnouncementBtn');
            const closeButtons = document.querySelectorAll('.close-modal-btn');
            const announcementForm = document.getElementById('announcementForm');
            const deleteForm = document.getElementById('deleteForm');
            const modalTitle = document.getElementById('modalTitle');
            const methodField = document.getElementById('methodField');

            addAnnouncementBtn.addEventListener('click', () => {
                announcementForm.reset();
                announcementForm.action = "{{ route('admin.announcements.store') }}";
                methodField.innerHTML = '';
                modalTitle.textContent = 'Add New Announcement';
                announcementModal.classList.add('active');
            });

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const announcement = JSON.parse(button.dataset.announcement);
                    announcementForm.reset();
                    announcementForm.action = `/admin/announcements/${announcement.id}`;
                    methodField.innerHTML = '@method("PUT")';
                    modalTitle.textContent = 'Edit Announcement';
                    document.getElementById('title').value = announcement.title;
                    document.getElementById('content').value = announcement.content;
                    announcementModal.classList.add('active');
                });
            });

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const announcementId = button.dataset.id;
                    deleteForm.action = `/admin/announcements/${announcementId}`;
                    deleteModal.classList.add('active');
                });
            });

            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    announcementModal.classList.remove('active');
                    deleteModal.classList.remove('active');
                });
            });
        });
    </script>
</script>
@endpush
