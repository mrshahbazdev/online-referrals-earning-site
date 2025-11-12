@extends('admin.layouts.app')

@section('title', 'Task Management')

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
    .form-group input, .form-group select { width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: #334155; color: var(--text-primary); }
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
        <h1>Task Management</h1>
        <button class="add-btn" id="addTaskBtn"><i class="ph ph-plus"></i> Add New Task</button>
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
                    <th>Title</th><th>Level</th><th>Reward</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->level->name ?? 'N/A' }}</td>
                        <td>${{ number_format($task->reward_amount, 2) }}</td>
                        <td>
                            <button class="action-btn edit-btn" data-task='@json($task)'><i class="ph ph-pencil-simple" style="color: var(--accent-color);"></i></button>
                            <button class="action-btn delete-btn" data-id="{{ $task->id }}"><i class="ph ph-trash" style="color: var(--red);"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center p-8">No tasks found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links">
        {{ $tasks->links() }}
    </div>

    <!-- Add/Edit Task Modal -->
    <div class="modal-overlay" id="taskModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Task</h2>
                <button class="close-modal-btn">&times;</button>
            </div>
            <form id="taskForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>
                <div class="form-group">
                    <label for="level_id">For Level</label>
                    <select id="level_id" name="level_id" required>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="task_type">Task Type</label>
                    <select id="task_type" name="task_type" required>
                        <option value="youtube_watch">YouTube - Watch</option>
                        <option value="youtube_like">YouTube - Like</option>
                        <option value="youtube_subscribe">YouTube - Subscribe</option>
                        <option value="tiktok_watch">TikTok - Watch</option>
                        <option value="tiktok_follow">TikTok - Follow</option>
                        <option value="facebook_like">Facebook - Like</option>
                    </select>
                </div>
                <div class="form-group"><label for="title">Task Title</label><input type="text" id="title" name="title" required></div>
                <div class="form-group"><label for="task_url">Task URL</label><input type="url" id="task_url" name="task_url" required></div>
                <div class="form-group"><label for="reward_amount">Reward Amount</label><input type="number" step="0.01" id="reward_amount" name="reward_amount" required></div>
                <div class="form-group"><label for="duration">Duration (seconds)</label><input type="number" id="duration" name="duration" value="0" required></div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary close-modal-btn">Cancel</button>
                    <button type="submit" class="btn-primary">Save Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header"><h2>Confirm Deletion</h2><button class="close-modal-btn">&times;</button></div>
            <p>Are you sure you want to delete this task?</p>
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
    const taskModal = document.getElementById('taskModal');
    const deleteModal = document.getElementById('deleteModal');
    const addTaskBtn = document.getElementById('addTaskBtn');
    const closeButtons = document.querySelectorAll('.close-modal-btn');
    const taskForm = document.getElementById('taskForm');
    const deleteForm = document.getElementById('deleteForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    addTaskBtn.addEventListener('click', () => {
        taskForm.reset();
        taskForm.action = "{{ route('admin.tasks.store') }}";
        methodField.innerHTML = '';
        modalTitle.textContent = 'Add New Task';
        taskModal.classList.add('active');
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', () => {
            const task = JSON.parse(button.dataset.task);
            taskForm.reset();
            taskForm.action = `/admin/tasks/${task.id}`;
            methodField.innerHTML = '@method("PUT")';
            modalTitle.textContent = 'Edit Task';

            document.getElementById('level_id').value = task.level_id;
            document.getElementById('task_type').value = task.task_type;
            document.getElementById('title').value = task.title;
            document.getElementById('task_url').value = task.task_url;
            document.getElementById('reward_amount').value = task.reward_amount;
            document.getElementById('duration').value = task.duration;

            taskModal.classList.add('active');
        });
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', () => {
            const taskId = button.dataset.id;
            deleteForm.action = `/admin/tasks/${taskId}`;
            deleteModal.classList.add('active');
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            taskModal.classList.remove('active');
            deleteModal.classList.remove('active');
        });
    });
});
</script>
@endpush
