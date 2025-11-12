@extends('admin.layouts.app')
@section('title', 'Website Settings')

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
    .form-group input[type="file"] { padding: 0; background: transparent; border: none; }
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
        <h1>Website Settings</h1>
    </header>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="bg-[#1E1F2B] p-6 rounded-lg">
        <form method="POST" action="{{ route('admin.settings.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <div class="form-group">
                    <label for="site_name">Website Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? config('app.name') }}" class="mt-1">
                </div>
                <div class="form-group">
                    <label for="site_logo">Website Logo</label>
                    <input type="file" name="site_logo" class="mt-1">
                    @if(isset($settings['site_logo']))
                        <div class="mt-4">
                            <p class="text-sm text-gray-400 mb-2">Current Logo:</p>
                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="w-32 mt-2 rounded-lg bg-white p-2">
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="header_scripts">Header Scripts (e.g., Google Console Code)</label>
                    <textarea name="header_scripts" rows="5" class="mt-1 font-mono text-sm">{{ $settings['header_scripts'] ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label for="whatsapp_number">WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" class="mt-1">
                </div>
                <div class="form-group">
                    <label for="terms_and_conditions">Terms and Conditions</label>
                    <textarea name="terms_and_conditions" id="terms-editor" rows="10">{{ $settings['terms_and_conditions'] ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label for="about_us_content">About Us Page Content</label>
                    <textarea name="about_us_content" id="about-us-editor" rows="10">{{ $settings['about_us_content'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="mt-6 text-right">
                <button type="submit" class="bg-yellow-400 text-black font-bold py-2 px-6 rounded-lg">Save Settings</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/huls9b76n59merr4fyfc4663g088tac6thaa1cknc4hrgg3f/tinymce/8/tinymce.min.js" referrerpolicy="origin"></script>

<script>
  tinymce.init({
    selector: '#terms-editor, #about-us-editor',
    plugins: 'code table lists link image media',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | link image media',
    skin: 'oxide-dark',
    content_css: 'dark'
  });
</script>
@endpush
