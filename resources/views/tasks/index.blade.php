@extends('layouts.frontend')

@section('title', 'Daily Tasks')

@section('content')
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold">Daily Tasks</h2>
        <p class="text-gray-400">Completed today: <span id="tasks-completed-count">{{ $tasksCompletedCount }}</span> / {{ $dailyLimit }}</p>
    </div>

    <!-- Active Task Timer (for non-video tasks) -->
    <div id="active-task-timer" class="hidden bg-[#1E1F2B] p-4 rounded-lg text-center space-y-2 mb-4">
        <p id="active-task-title" class="font-bold"></p>
        <p class="text-sm text-gray-400">Time Remaining:</p>
        <p id="link-task-timer" class="text-3xl font-bold text-yellow-400">00:00</p>
        <button id="claim-reward-btn" class="w-full bg-gray-600 text-gray-400 font-bold py-2 rounded-lg cursor-not-allowed" disabled>Claim Reward</button>
    </div>

    <!-- Task List -->
    <div class="space-y-4" id="task-list">
        @forelse ($tasks as $task)
            <div class="bg-[#1E1F2B] p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        @if(Str::startsWith($task->task_type, 'youtube'))
                            <i class="ph-bold ph-youtube-logo text-3xl text-red-500"></i>
                        @elseif(Str::startsWith($task->task_type, 'tiktok'))
                            <i class="ph-bold ph-tiktok-logo text-3xl text-white"></i>
                        @elseif(Str::startsWith($task->task_type, 'facebook'))
                            <i class="ph-bold ph-facebook-logo text-3xl text-blue-500"></i>
                        @endif
                        <div>
                            <p class="font-bold">{{ $task->title }}</p>
                            <p class="text-xs text-gray-400">Reward: <span class="font-semibold text-yellow-400">${{ number_format($task->reward_amount, 2) }}</span></p>
                        </div>
                    </div>
                    @if($task->task_type === 'youtube_watch')
                        <button class="task-button bg-gray-600 text-gray-400 text-sm font-bold px-4 py-2 rounded-lg cursor-not-allowed" data-task-id="{{ $task->id }}" data-duration="{{ $task->duration }}" data-video-id="{{ $task->youtube_id }}" disabled>Loading...</button>
                    @else
                        <button class="task-link-button bg-gray-600 text-gray-400 text-sm font-bold px-4 py-2 rounded-lg cursor-not-allowed" data-task-id="{{ $task->id }}" data-duration="{{ $task->duration }}" data-task-url="{{ $task->task_url }}" data-task-title="{{ $task->title }}" disabled>Loading...</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-[#1E1F2B] p-4 rounded-lg text-center text-gray-400">
                <p>No more tasks available for today.</p>
            </div>
        @endforelse
    </div>
@endsection

@push('styles')
<style>
    .toast {
        position: fixed; top: 20px; right: 20px; background-color: #1E2B3B; color: #f1f5f9;
        padding: 1rem; border-radius: 8px; border-left: 5px solid #475569;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3); display: flex; align-items: center;
        gap: 1rem; z-index: 2000; transform: translateX(120%);
        transition: transform 0.5s ease-in-out;
    }
    .toast.show { transform: translateX(0); }
    .toast.success { background-color: #166534; color: #dcfce7; border-left-color: #22c55e; }
    .toast.error { background-color: #991b1b; color: #fee2e2; border-left-color: #ef4444; }
    .toast i { font-size: 1.5rem; }
    .loader { 
        border: 2px solid #f3f3f3; border-top: 2px solid #10111A; border-radius: 50%; 
        width: 16px; height: 16px; animation: spin 1s linear infinite; display: inline-block;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>
@endpush

@push('scripts')
    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i id="toast-icon" class="ph"></i>
        <div id="toast-message"></div>
    </div>

    <!-- Video Player Modal -->
    <div id="video-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
        <div class="bg-[#10111A] rounded-2xl p-4 w-full max-w-sm text-center space-y-4">
            <h3 class="font-bold text-lg">Watching Video...</h3>
            <div class="aspect-video bg-black rounded-lg">
                <div id="player"></div>
            </div>
            <div class="bg-[#1E1F2B] p-4 rounded-lg">
                <p class="text-sm text-gray-400">Time Remaining:</p>
                <p id="timer" class="text-4xl font-bold text-yellow-400">00:00</p>
            </div>
            <button id="close-modal" class="w-full bg-red-600 text-white font-bold py-2 rounded-lg">Cancel</button>
        </div>
    </div>

    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // **FIX:** Page refresh par purana lock hamesha remove karein
            localStorage.removeItem('active_task_lock');

            const TASK_LOCK_KEY = 'active_task_lock';
            
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');
            const completedCountSpan = document.getElementById('tasks-completed-count');
            const taskList = document.getElementById('task-list');

            const activeTaskTimerDiv = document.getElementById('active-task-timer');
            const activeTaskTitle = document.getElementById('active-task-title');
            const linkTaskTimerDisplay = document.getElementById('link-task-timer');
            const claimRewardBtn = document.getElementById('claim-reward-btn');
            let linkTaskInterval, taskWindow;

            const videoModal = document.getElementById('video-modal');
            const closeModalButton = document.getElementById('close-modal');
            const timerDisplay = document.getElementById('timer');
            let player, videoTimerInterval, videoTimeLeft, currentVideoButton, currentVideoTaskId;

            window.onYouTubeIframeAPIReady = function() {
                player = new YT.Player('player', {
                    height: '100%', width: '100%',
                    playerVars: { 'playsinline': 1, 'controls': 0, 'rel': 0, 'origin': window.location.origin },
                    events: { 'onReady': onPlayerReady, 'onStateChange': onPlayerStateChange }
                });
            }

            function onPlayerReady(event) {
                activateAllTaskButtons();
            }

            function activateAllTaskButtons() {
                document.querySelectorAll('.task-link-button').forEach(button => {
                    button.disabled = false;
                    button.innerHTML = 'Go to Task';
                    button.classList.remove('bg-gray-600', 'text-gray-400', 'cursor-not-allowed');
                    button.classList.add('bg-yellow-400', 'text-black');
                });
                document.querySelectorAll('.task-button').forEach(button => {
                    button.disabled = false;
                    button.innerHTML = 'Watch';
                    button.classList.remove('bg-gray-600', 'text-gray-400', 'cursor-not-allowed');
                    button.classList.add('bg-yellow-400', 'text-black');
                });
                checkTaskLock();
            }

            taskList.addEventListener('click', function(event) {
                const button = event.target.closest('.task-button, .task-link-button');
                if (!button || button.disabled) return;

                if (button.classList.contains('task-button')) {
                    handleVideoTask(button);
                } else if (button.classList.contains('task-link-button')) {
                    handleLinkTask(button);
                }
            });

            function handleLinkTask(button) {
                if (isTaskActive()) return;
                
                button.innerHTML = '<span class="loader"></span> Starting...';
                
                const taskId = button.dataset.taskId;
                const taskUrl = button.dataset.taskUrl;
                const duration = parseInt(button.dataset.duration, 10);
                
                taskWindow = window.open(taskUrl, 'taskWindow', 'width=800,height=600');
                
                if (!taskWindow || taskWindow.closed || typeof taskWindow.closed == 'undefined') {
                    showToast('Please allow popups for this site to start the task.', 'error');
                    resetTaskState(button, 'Go to Task');
                    return;
                }

                setTaskActive(true);
                disableAllButtons();
                button.textContent = 'In Progress...';
                activeTaskTitle.textContent = button.dataset.taskTitle;
                activeTaskTimerDiv.classList.remove('hidden');
                
                let timeLeft = duration;
                linkTaskTimerDisplay.textContent = formatTime(timeLeft);

                linkTaskInterval = setInterval(() => {
                    if (taskWindow.closed) {
                        clearInterval(linkTaskInterval);
                        resetTaskState(button, 'Go to Task');
                        showToast('Task cancelled because the window was closed.', 'error');
                        return;
                    }

                    timeLeft--;
                    linkTaskTimerDisplay.textContent = formatTime(timeLeft);

                    if (timeLeft <= 0) {
                        clearInterval(linkTaskInterval);
                        claimRewardBtn.disabled = false;
                        claimRewardBtn.textContent = 'Claim Reward';
                        claimRewardBtn.classList.remove('bg-gray-600', 'text-gray-400', 'cursor-not-allowed');
                        claimRewardBtn.classList.add('bg-green-500', 'text-white');
                        claimRewardBtn.onclick = () => completeTask(button, taskId);
                    }
                }, 1000);
            }

            function handleVideoTask(button) {
                if (isTaskActive()) return;
                
                button.innerHTML = '<span class="loader"></span> Starting...';

                if (player && typeof player.loadVideoById === 'function') {
                    setTaskActive(true);
                    disableAllButtons();
                    
                    currentVideoButton = button;
                    currentVideoTaskId = button.dataset.taskId;
                    videoTimeLeft = parseInt(button.dataset.duration, 10);
                    timerDisplay.textContent = formatTime(videoTimeLeft);
                    videoModal.classList.remove('hidden');
                    player.loadVideoById(button.dataset.videoId);
                } else {
                    alert('YouTube player is not ready yet. Please wait a moment and try again.');
                    resetTaskState(button, 'Watch');
                }
            }
            
            function onPlayerStateChange(event) {
                if (event.data == YT.PlayerState.PLAYING) { startVideoTimer(); } 
                else { pauseVideoTimer(); }
            }

            function startVideoTimer() {
                clearInterval(videoTimerInterval);
                videoTimerInterval = setInterval(() => {
                    videoTimeLeft--;
                    timerDisplay.textContent = formatTime(videoTimeLeft);
                    if (videoTimeLeft <= 0) {
                        clearInterval(videoTimerInterval);
                        player.stopVideo();
                        videoModal.classList.add('hidden');
                        completeTask(currentVideoButton, currentVideoTaskId);
                    }
                }, 1000);
            }

            function pauseVideoTimer() { clearInterval(videoTimerInterval); }

            closeModalButton.addEventListener('click', () => {
                clearInterval(videoTimerInterval);
                videoModal.classList.add('hidden');
                if (player && typeof player.stopVideo === 'function') {
                    player.stopVideo();
                }
                resetTaskState(currentVideoButton, 'Watch');
            });

            function isTaskActive() {
                if (localStorage.getItem(TASK_LOCK_KEY)) {
                    showToast('Another task is already in progress.', 'error');
                    return true;
                }
                return false;
            }

            function setTaskActive(isActive) {
                if (isActive) {
                    localStorage.setItem(TASK_LOCK_KEY, 'true');
                } else {
                    localStorage.removeItem(TASK_LOCK_KEY);
                }
            }

            function disableAllButtons() {
                document.querySelectorAll('.task-button, .task-link-button').forEach(btn => {
                    if (!btn.textContent.includes('Completed')) {
                        btn.disabled = true;
                    }
                });
            }

            function resetTaskState(button, originalText) {
                setTaskActive(false);
                activeTaskTimerDiv.classList.add('hidden');
                claimRewardBtn.disabled = true;
                claimRewardBtn.textContent = 'Claim Reward';
                claimRewardBtn.classList.add('bg-gray-600', 'text-gray-400', 'cursor-not-allowed');
                claimRewardBtn.classList.remove('bg-green-500', 'text-white');
                
                document.querySelectorAll('.task-button, .task-link-button').forEach(btn => {
                   if (!btn.textContent.includes('Completed')) {
                       btn.disabled = false;
                       if(btn === button) btn.innerHTML = originalText;
                   }
                });
            }

            function checkTaskLock() {
                if (localStorage.getItem(TASK_LOCK_KEY)) {
                    disableAllButtons();
                }
            }

            window.addEventListener('storage', (event) => {
                if (event.key === TASK_LOCK_KEY) {
                    if (event.newValue) {
                        disableAllButtons();
                    } else {
                        location.reload();
                    }
                }
            });

            function formatTime(seconds) {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            }

            function showToast(message, type = 'success') {
                toast.classList.remove('success', 'error');
                if (type === 'success') {
                    toast.classList.add('success');
                    toastIcon.className = 'ph ph-check-circle';
                } else {
                    toast.classList.add('error');
                    toastIcon.className = 'ph ph-x-circle';
                }
                toastMessage.innerHTML = message;
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 4000);
            }

            function completeTask(button, taskId) {
                if(taskWindow && !taskWindow.closed) {
                    taskWindow.close();
                }

                button.innerHTML = '<span class="loader"></span> Processing...';
                button.disabled = true;
                if (claimRewardBtn) {
                    claimRewardBtn.disabled = true;
                    claimRewardBtn.textContent = 'Claiming...';
                }

                fetch(`/tasks/${taskId}/complete`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.parentElement.parentElement.parentElement.style.display = 'none';
                        showToast(`Task completed!<br>New Balance: $${parseFloat(data.new_balance).toFixed(2)}`);
                        
                        let currentCount = parseInt(completedCountSpan.textContent, 10);
                        completedCountSpan.textContent = currentCount + 1;
                        
                        resetTaskState(button, 'Completed');
                    } else {
                        showToast(data.error || 'An unknown error occurred.', 'error');
                        resetTaskState(button, 'Go to Task');
                    }
                });
            }
        });
    </script>
@endpush
