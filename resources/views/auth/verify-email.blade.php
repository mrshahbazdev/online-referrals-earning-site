@extends('layouts.frontend')

@section('title', 'Verify Your Email')

@section('content')
<div class="bg-[#1E1F2B] p-8 rounded-lg text-center space-y-6">
    <i class="ph-bold ph-envelope-simple-open text-5xl text-yellow-400"></i>
    <h2 class="text-2xl font-bold text-white">Verify Your Email Address</h2>

    <p class="text-gray-400">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-400">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-2 px-4 rounded-lg hover:bg-yellow-500 transition-colors">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-gray-400 hover:text-white underline text-sm">
                Log Out
            </button>
        </form>
    </div>
</div>
@endsection
