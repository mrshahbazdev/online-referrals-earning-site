@extends('admin.layouts.app')

@section('title', 'Dashboard Management')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endpush
@push('styles')


    <style>
        :root {
            --bg-dark: #111827;
            --sidebar-bg: #1E293B;
            --card-bg: #1E293B;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-color: #facc15;
            --border-color: #334155;
            --green: #22c55e;
            --red: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-primary);
        }

        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2.5rem;
        }

        .sidebar-header .icon {
            font-size: 2rem;
            color: var(--accent-color);
        }

        .sidebar-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .sidebar-nav {
            flex-grow: 1;
        }

        .sidebar-nav ul {
            list-style: none;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar-nav a:hover {
            background-color: #334155;
            color: var(--text-primary);
        }

        .sidebar-nav a.active {
            background-color: var(--accent-color);
            color: var(--bg-dark);
            font-weight: 600;
        }

        .sidebar-nav a i {
            font-size: 1.25rem;
        }

        .logout-form button {
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
        }

        .logout-link {
             color: var(--text-secondary) !important;
        }
        .logout-link:hover {
            color: var(--text-primary) !important;
        }

        /* Main Content Styling */
        .main-content {
            flex-grow: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .main-header h1 {
            font-size: 1.75rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .stat-card-header i {
            font-size: 1.5rem;
        }

        .stat-card .value {
            font-size: 2.25rem;
            font-weight: 700;
        }

        .chart-container {
            background-color: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

    </style>
@endpush
@section('content')
<header class="main-header">
                <h1>Dashboard</h1>
                <div class="admin-profile">
                    <span>Welcome, {{ Auth::user()->username }}</span>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span>Total Users</span>
                        <i class="ph ph-users-three" style="color: #60a5fa;"></i>
                    </div>
                    <p class="value">{{ $totalUsers }}</p>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span>Total Revenue</span>
                        <i class="ph ph-currency-dollar" style="color: var(--green);"></i>
                    </div>
                    <p class="value">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span>Pending KYC</span>
                        <i class="ph ph-warning-circle" style="color: var(--accent-color);"></i>
                    </div>
                    <p class="value">{{ $pendingKyc }}</p>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span>Pending Withdrawals</span>
                        <i class="ph ph-clock-countdown" style="color: var(--red);"></i>
                    </div>
                    <p class="value">{{ $pendingWithdrawals }}</p>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="chart-container">
                <canvas id="registrationsChart"></canvas>
            </div>
        </main>
    </div>
    @push('scripts')


    <script>
        const ctx = document.getElementById('registrationsChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'New Users This Week',
                    data: @json($chartData),
                    borderColor: 'rgba(250, 204, 21, 1)',
                    backgroundColor: 'rgba(250, 204, 21, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: 'var(--text-secondary)' },
                        grid: { color: 'var(--border-color)' }
                    },
                    x: {
                        ticks: { color: 'var(--text-secondary)' },
                        grid: { color: 'var(--border-color)' }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'var(--text-primary)'
                        }
                    }
                }
            }
        });
    </script>
@endpush
@endsection

