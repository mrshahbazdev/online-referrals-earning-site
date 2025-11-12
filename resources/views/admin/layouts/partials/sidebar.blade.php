<!-- Sidebar Header -->
<div class="sidebar-header">
    <i class="ph-bold ph-shield-check icon"></i>
    <h2>Admin Panel</h2>
</div>

<!-- Sidebar Navigation -->
<nav class="sidebar-nav">
    <ul>
        <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="ph ph-gauge"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="ph ph-users"></i> User Management</a></li>
        <li><a href="{{ route('admin.levels.index') }}" class="{{ request()->routeIs('admin.levels.*') ? 'active' : '' }}"><i class="ph ph-stairs"></i> Level Management</a></li>
        <li><a href="{{ route('admin.tasks.index') }}" class="{{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}"><i class="ph ph-list-checks"></i> Task Management</a></li>
        <li><a href="{{ route('admin.kyc.index') }}" class="{{ request()->routeIs('admin.kyc.*') ? 'active' : '' }}"><i class="ph ph-identification-card"></i> KYC Submissions</a></li>
        <li><a href="{{ route('admin.investments.index') }}" class="{{ request()->routeIs('admin.investments.*') ? 'active' : '' }}"><i class="ph ph-chart-line-up"></i> Investment Requests</a></li>
        <li><a href="{{ route('admin.withdrawals.index') }}" class="{{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}"><i class="ph ph-arrow-circle-down"></i> Withdrawal Requests</a></li>
        <li><a href="{{ route('admin.deposit-methods.index') }}" class="{{ request()->routeIs('admin.deposit-methods.*') ? 'active' : '' }}"><i class="ph ph-bank"></i> Deposit Methods</a></li>
        <li><a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}"><i class="ph ph-megaphone"></i> Announcements</a></li>
        <li><a href="{{ route('admin.admins.index') }}" class="{{ request()->routeIs('admin.admins.*') ? 'active' : '' }}"><i class="ph ph-user-gear"></i> Admin Management</a></li>
        <li><a href="{{ route('admin.activity_logs.index') }}" class="{{ request()->routeIs('admin.activity_logs.*') ? 'active' : '' }}"><i class="ph ph-list-dashes"></i> Admin Log</a></li>
        <li><a href="{{ route('admin.user_activity.index') }}" class="{{ request()->routeIs('admin.user_activity.*') ? 'active' : '' }}"><i class="ph ph-user-list"></i> User Log</a></li>
        {{-- settings link --}}
        <li><a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="ph ph-gear"></i> Settings</a></li>
    </ul>
</nav>

<!-- Logout Button -->
<div class="logout-section">
    <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
        @csrf
        <button type="submit">
            <a class="logout-link"><i class="ph ph-sign-out"></i> Logout</a>
        </button>
    </form>
</div>
