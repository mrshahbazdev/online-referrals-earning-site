@extends('layouts.frontend')

@section('title', 'My Profile')

@push('styles')
<style>
    .tab.active { background-color: #facc15; color: #10111A; }
    .toast { position: fixed; top: 20px; right: 20px; background-color: #166534; color: #dcfce7; padding: 1rem; border-radius: 8px; z-index: 2000; transform: translateX(120%); transition: transform 0.5s ease-in-out; }
    .toast.show { transform: translateX(0); }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Profile Picture -->
    <div class="flex flex-col items-center space-y-2">
        <div class="relative">
            <img id="profileImagePreview" src="{{ $user->profile_image_url ? asset('storage/app/public/' . $user->profile_image_url) : 'https://placehold.co/96x96/fbbF24/10111A?text=' . strtoupper(substr($user->username, 0, 1)) }}" alt="Profile Picture" class="w-24 h-24 rounded-full border-2 border-yellow-400 object-cover">
            <label for="profileImageInput" class="absolute bottom-0 right-0 bg-yellow-400 text-black p-2 rounded-full cursor-pointer">
                <i class="ph ph-camera"></i>
            </label>
            <input type="file" id="profileImageInput" class="hidden" accept="image/*">
        </div>
        <span class="font-semibold">Username: {{ $user->username }}</span>
    </div>

    <!-- Filter Tabs -->
    <div id="filter-tabs" class="grid grid-cols-3 gap-2 bg-[#1E1F2B] p-1 rounded-full">
        <button data-tab="account" class="tab active w-full py-2 text-sm font-bold rounded-full transition-colors">Account</button>
        <button data-tab="password" class="tab w-full py-2 text-sm font-bold rounded-full transition-colors text-gray-400">Password</button>
        <button data-tab="delete" class="tab w-full py-2 text-sm font-bold rounded-full transition-colors text-gray-400">Delete</button>
    </div>

    <!-- Form Content -->
    <div id="form-content">
        <!-- Account Form -->
        <form id="account-form" method="post" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('patch')
            <h3 class="font-bold text-lg">User Information</h3>
            @if (session('status') === 'profile-updated')
                <p class="text-sm text-green-400">Saved.</p>
            @endif
            <div>
                <label for="username" class="text-sm font-medium text-gray-400">Username</label>
                <input type="text" id="username" name="username" value="{{ $user->username }}" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3 text-gray-500 cursor-not-allowed" readonly>
            </div>
            <div>
                <label for="email" class="text-sm font-medium text-gray-400">Email</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3 text-gray-500 cursor-not-allowed" readonly>
            </div>
            <div>
                <label for="first-name" class="text-sm font-medium text-gray-400">First Name</label>
                <input type="text" id="first-name" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3">
            </div>
            <div>
                <label for="last-name" class="text-sm font-medium text-gray-400">Last Name</label>
                <input type="text" id="last-name" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3">
            </div>
            <div>
                <label for="mobile" class="text-sm font-medium text-gray-400">Mobile Number</label>
                <input type="tel" id="mobile" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3">
            </div>
            <div>
                <label for="address" class="text-sm font-medium text-gray-400">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3">
            </div>
            <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg mt-6">Update Information</button>
        </form>

        <!-- Password Form -->
        <form id="password-form" method="post" action="{{ route('password.update') }}" class="hidden space-y-4">
            @csrf
            @method('put')
            <h3 class="font-bold text-lg">Change Password</h3>
            @if (session('status') === 'password-updated')
                <p class="text-sm text-green-400">Saved.</p>
            @endif
            <div>
                <label for="current_password" class="text-sm font-medium text-gray-400">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3" required>
                @error('current_password', 'updatePassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="password" class="text-sm font-medium text-gray-400">New Password</label>
                <input type="password" id="password" name="password" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3" required>
                 @error('password', 'updatePassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="text-sm font-medium text-gray-400">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3" required>
            </div>
            <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg mt-6">Update Password</button>
        </form>

        <!-- Delete Account Form -->
        <form id="delete-form" method="post" action="{{ route('profile.destroy') }}" class="hidden space-y-4">
            @csrf
            @method('delete')
            <h3 class="font-bold text-lg text-red-400">Delete Account</h3>
            <p class="text-sm text-gray-400">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.</p>
            <div>
                <label for="password_delete" class="text-sm font-medium text-gray-400">Password</label>
                <input type="password" id="password_delete" name="password" class="mt-1 w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3" required>
                 @error('password', 'userDeletion') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded-lg mt-6">Delete Account</button>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast">Profile image updated!</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabsContainer = document.getElementById('filter-tabs');
    const forms = {
        account: document.getElementById('account-form'),
        password: document.getElementById('password-form'),
        delete: document.getElementById('delete-form'),
    };

    tabsContainer.addEventListener('click', (e) => {
        const clickedTab = e.target.closest('.tab');
        if (!clickedTab) return;

        tabsContainer.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        clickedTab.classList.add('active');

        Object.values(forms).forEach(form => form.classList.add('hidden'));
        forms[clickedTab.dataset.tab].classList.remove('hidden');
    });

    // AJAX for Profile Image Upload
    const profileImageInput = document.getElementById('profileImageInput');
    const profileImagePreview = document.getElementById('profileImagePreview');
    const toast = document.getElementById('toast');

    profileImageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const formData = new FormData();
            formData.append('profile_image', this.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("profile.image.update") }}', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' },
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    profileImagePreview.src = data.image_url;
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 3000);
                } else {
                    alert('Image upload failed. Please try again.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
</script>
@endpush
