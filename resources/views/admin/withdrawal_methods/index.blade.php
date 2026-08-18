@extends('admin.layouts.app')

@section('title', 'Withdrawal Methods')

@push('styles')
<style>
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
    .form-group input, .form-group select { width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: #334155; color: var(--text-primary); }
    .modal-footer { margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; }
    .modal-footer button { padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; }
    .btn-secondary { background-color: #475569; color: var(--text-primary); }
    .btn-primary { background-color: var(--accent-color); color: var(--bg-dark); }
</style>
@endpush

@section('content')
    <header class="main-header">
        <button class="mobile-nav-toggle" id="mobileNavToggle"><i class="ph ph-list"></i></button>
        <h1>Withdrawal Methods</h1>
        <button class="add-btn" id="addMethodBtn"><i class="ph ph-plus"></i> Add New Method</button>
    </header>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Method Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($methods as $method)
                    <tr>
                        <td>{{ $method->name }}</td>
                        <td>
                            @if($method->is_active)
                                <span style="background-color: rgba(34, 197, 94, 0.2); color: #4ade80; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Active</span>
                            @else
                                <span style="background-color: rgba(239, 68, 68, 0.2); color: #f87171; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button class="action-btn text-blue-400" onclick="editMethod({{ $method->id }}, '{{ addslashes($method->name) }}', {{ $method->is_active ? 'true' : 'false' }})" title="Edit"><i class="ph ph-pencil-simple"></i></button>
                            <form action="{{ route('admin.withdrawal-methods.destroy', $method) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn text-red-400" title="Delete" onclick="return confirm('Are you sure you want to delete this method?');"><i class="ph ph-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center;">No withdrawal methods found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links">
        {{ $methods->links() }}
    </div>

    <!-- Add Modal -->
    <div class="modal-overlay" id="addModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Withdrawal Method</h2>
                <button class="close-modal-btn" onclick="closeModal('addModal')">&times;</button>
            </div>
            <form action="{{ route('admin.withdrawal-methods.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="add_name">Method Name (e.g. USDT TRC20, JazzCash, Bank Transfer)</label>
                    <input type="text" id="add_name" name="name" required>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" checked style="width:auto; display:inline-block;"> Active
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('addModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Add Method</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Withdrawal Method</h2>
                <button class="close-modal-btn" onclick="closeModal('editModal')">&times;</button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit_name">Method Name</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="edit_is_active" name="is_active" value="1" style="width:auto; display:inline-block;"> Active
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('editModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Update Method</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    document.getElementById('addMethodBtn').addEventListener('click', function() {
        openModal('addModal');
    });

    function editMethod(id, name, isActive) {
        document.getElementById('editForm').action = '/admin/withdrawal-methods/' + id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_is_active').checked = isActive;
        
        openModal('editModal');
    }
</script>
@endpush
