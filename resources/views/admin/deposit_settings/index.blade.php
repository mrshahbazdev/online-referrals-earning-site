@extends('admin.layouts.app')

@section('title', 'Deposit Settings')

@push('styles')
<style>
    .alert { padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid; }
    .alert-success { background-color: #166534; border-color: var(--green); color: #dcfce7; }
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-secondary); }
    .form-group input, .form-group textarea {
        width: 100%; padding: 0.75rem; border-radius: 8px;
        border: 1px solid var(--border-color); background: #334155;
        color: var(--text-primary);
    }
    .form-group input[type="file"] {
        padding: 0;
        background: transparent; border: none;
    }
    .form-group input[type="file"]::file-selector-button {
        margin-right: 1rem; padding: 0.6rem 1.2rem; border-radius: 8px;
        font-weight: 600; cursor: pointer; border: none;
        background-color: var(--accent-color); color: var(--bg-dark);
    }
</style>
@endpush

@section('content')
    <header class="main-header">
        <button class="mobile-nav-toggle" id="mobileNavToggle"><i class="ph ph-list"></i></button>
        <h1>Deposit Settings</h1>
    </header>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="bg-[#1E1F2B] p-6 rounded-lg">
        <form method="POST" action="{{ route('admin.deposit.settings.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-yellow-400 border-b border-gray-700 pb-2 mb-4">Deposit Information</h3>
                </div>

                <div class="form-group">
                    <label for="network">Network (e.g., Tron (TRC20))</label>
                    <input type="text" id="network" name="network" value="{{ old('network', $details->network) }}">
                </div>

                <div class="form-group">
                    <label for="address">Deposit Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $details->address) }}">
                </div>

                <div class="form-group md:col-span-2">
                    <label for="qr_code">QR Code Image</label>
                    <input type="file" id="qr_code" name="qr_code" class="mt-1">
                    @if($details->qr_code_url)
                        <div class="mt-4">
                            <p class="text-sm text-gray-400 mb-2">Current QR Code:</p>
                            <img src="{{ asset('storage/' . $details->qr_code_url) }}" alt="QR Code" class="w-32 h-32 rounded-lg bg-white p-1">
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 text-right">
                <button type="submit" class="bg-yellow-400 text-black font-bold py-2 px-6 rounded-lg">Save Settings</button>
            </div>
        </form>
    </div>
@endsection
