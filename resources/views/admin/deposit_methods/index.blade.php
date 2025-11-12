@extends('admin.layouts.app')

@section('title', 'Deposit Methods')

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
    .qr-code-img { width: 40px; height: 40px; border-radius: 4px; object-fit: cover; }

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
</style>
@endpush

@section('content')
    <header class="main-header">
        <button class="mobile-nav-toggle" id="mobileNavToggle"><i class="ph ph-list"></i></button>
        <h1>Deposit Methods</h1>
        <button class="add-btn" id="addMethodBtn"><i class="ph ph-plus"></i> Add New Method</button>
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
                    <th>Name</th>
                    <th>Network</th>
                    <th>Address</th>
                    <th>QR Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($methods as $method)
                    <tr>
                        <td>{{ $method->name }}</td>
                        <td>{{ $method->network }}</td>
                        <td>{{ Str::limit($method->address, 20) }}</td>
                        <td>
                            @if($method->qr_code_url)
                                <img src="{{ asset('storage/' . $method->qr_code_url) }}" alt="QR" class="qr-code-img">
                            @endif
                        </td>
                        <td>
                            <button class="action-btn edit-btn" data-method='@json($method)'><i class="ph ph-pencil-simple" style="color: var(--accent-color);"></i></button>
                            <button class="action-btn delete-btn" data-id="{{ $method->id }}"><i class="ph ph-trash" style="color: var(--red);"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center p-8">No deposit methods found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links">
        {{ $methods->links() }}
    </div>

    <!-- Add/Edit Method Modal -->
    <div class="modal-overlay" id="methodModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Method</h2>
                <button class="close-modal-btn">&times;</button>
            </div>
            <form id="methodForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>
                <div class="form-group"><label for="name">Method Name (e.g., USDT TRC20)</label><input type="text" id="name" name="name" required></div>
                <div class="form-group"><label for="network">Network</label><input type="text" id="network" name="network" required></div>
                <div class="form-group"><label for="address">Deposit Address</label><input type="text" id="address" name="address" required></div>
                <div class="form-group"><label for="qr_code">QR Code Image</label><input type="file" id="qr_code" name="qr_code"></div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-primary">Save Method</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header"><h2>Confirm Deletion</h2><button class="close-modal-btn">&times;</button></div>
            <p>Are you sure you want to delete this method?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const methodModal = document.getElementById('methodModal');
    const deleteModal = document.getElementById('deleteModal');
    const addMethodBtn = document.getElementById('addMethodBtn');
    const closeButtons = document.querySelectorAll('.close-modal-btn');
    const methodForm = document.getElementById('methodForm');
    const deleteForm = document.getElementById('deleteForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    addMethodBtn.addEventListener('click', () => {
        methodForm.reset();
        methodForm.action = "{{ route('admin.deposit-methods.store') }}";
        methodField.innerHTML = '';
        modalTitle.textContent = 'Add New Method';
        methodModal.classList.add('active');
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', () => {
            const method = JSON.parse(button.dataset.method);
            methodForm.reset();
            methodForm.action = `/admin/deposit-methods/${method.id}`;
            methodField.innerHTML = '@method("PUT")';
            modalTitle.textContent = 'Edit Method';

            document.getElementById('name').value = method.name;
            document.getElementById('network').value = method.network;
            document.getElementById('address').value = method.address;

            methodModal.classList.add('active');
        });
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', () => {
            const methodId = button.dataset.id;
            deleteForm.action = `/admin/deposit-methods/${methodId}`;
            deleteModal.classList.add('active');
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            methodModal.classList.remove('active');
            deleteModal.classList.remove('active');
        });
    });
});
</script>
@endpush
