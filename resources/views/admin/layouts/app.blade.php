<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - {{ $settings['site_name'] ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        .sidebar-nav { flex-grow: 1; overflow-y: auto; }
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
        .mobile-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99; }
        @media (max-width: 768px) {
            .sidebar { position: fixed; left: 0; top: 0; height: 100%; transform: translateX(-100%); z-index: 100; }
            .sidebar.active { transform: translateX(0); }
            .mobile-nav-toggle { display: block; }
            .mobile-overlay.active { display: block; }
        }
        /* Baqi tamam pages ke common styles yahan add karein */
    </style>
    @stack('styles')
</head>
<body>
    <div class="dashboard-layout">
        <div class="mobile-overlay" id="mobileOverlay"></div>
        <aside class="sidebar" id="sidebar">
            @include('admin.layouts.partials.sidebar')
        </aside>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');

            if (mobileNavToggle) {
                mobileNavToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                    mobileOverlay.classList.toggle('active');
                });
            }
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    mobileOverlay.classList.remove('active');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
