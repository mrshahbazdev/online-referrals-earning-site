@extends('layouts.frontend')

@section('title', 'Terms and Conditions')

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <i class="ph-bold ph-file-text text-5xl text-yellow-400"></i>
            <h2 class="text-2xl font-bold text-white mt-4">Terms and Conditions</h2>
        </div>

        <div class="bg-[#1E1F2B] p-4 rounded-lg space-y-4 text-gray-300 text-sm prose">
            {!! $settings['terms_and_conditions'] ?? 'No terms and conditions have been set yet.' !!}
        </div>
    </div>
@endsection
