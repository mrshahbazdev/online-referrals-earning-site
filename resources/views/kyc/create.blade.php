@extends('layouts.frontend')

@section('title', 'KYC Verification')

@section('content')
    <div class="space-y-6">
        <h2 class="text-xl font-bold text-center mb-6">KYC Verification Status</h2>

        <!-- Status Display -->
        <div class="bg-[#1E1F2B] p-6 rounded-lg flex flex-col items-center text-center">
            @if($user->kyc_status == 'approved')
                <i class="ph-bold ph-check-circle text-6xl text-green-400"></i>
                <h3 class="text-2xl font-bold mt-4">Verified</h3>
                <p class="text-sm text-gray-400 mt-1">Your account is fully verified. You have access to all features.</p>
            @elseif($user->kyc_status == 'pending')
                <i class="ph-bold ph-clock text-6xl text-yellow-400"></i>
                <h3 class="text-2xl font-bold mt-4">Pending Review</h3>
                <p class="text-sm text-gray-400 mt-1">Your documents are being reviewed. This may take up to 24 hours.</p>
            @elseif($user->kyc_status == 'rejected')
                <i class="ph-bold ph-x-circle text-6xl text-red-400"></i>
                <h3 class="text-2xl font-bold mt-4">Rejected</h3>
                <p class="text-sm text-gray-400 mt-1">Your submission was rejected. Please check the details and submit again.</p>
            @else {{-- Unverified --}}
                <i class="ph-bold ph-identification-card text-6xl text-gray-400"></i>
                <h3 class="text-2xl font-bold mt-4">Not Verified</h3>
                <p class="text-sm text-gray-400 mt-1">Please submit your documents to get verified.</p>
            @endif
        </div>

        @if($lastSubmission)
        <!-- KYC Details -->
        <div class="bg-[#1E1F2B] p-4 rounded-lg space-y-3">
             <div class="flex justify-between text-sm">
                <span class="text-gray-400">Name:</span>
                <span class="font-semibold">{{ $lastSubmission->full_name }}</span>
            </div>
             <div class="flex justify-between text-sm">
                <span class="text-gray-400">ID Number:</span>
                <span class="font-semibold">{{ $lastSubmission->id_card_number }}</span>
            </div>
             <div class="flex justify-between text-sm">
                <span class="text-gray-400">Submission Date:</span>
                <span class="font-semibold">{{ $lastSubmission->created_at->format('d M, Y') }}</span>
            </div>
             <div class="flex justify-between text-sm">
                <span class="text-gray-400">Verification Date:</span>
                <span class="font-semibold">{{ $lastSubmission->status != 'pending' ? $lastSubmission->updated_at->format('d M, Y') : 'N/A' }}</span>
            </div>
        </div>
        @endif

        <!-- Show Form only if user can submit -->
        @if($user->kyc_status == 'unverified' || $user->kyc_status == 'rejected')
            <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data" class="space-y-4 bg-[#1E1F2B] p-6 rounded-lg">
                @csrf
                <h3 class="font-bold text-lg text-center">Submit Your Documents</h3>
                <div>
                    <label for="full_name" class="text-sm font-medium text-gray-400">Full Name (as on ID)</label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" class="mt-1 w-full bg-[#334155] border-gray-700 rounded-lg p-3" required>
                    @error('full_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="id_card_number" class="text-sm font-medium text-gray-400">ID Card Number</label>
                    <input type="text" id="id_card_number" name="id_card_number" value="{{ old('id_card_number') }}" class="mt-1 w-full bg-[#334155] border-gray-700 rounded-lg p-3" required>
                    @error('id_card_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="id_card_front" class="text-sm font-medium text-gray-400">ID Card (Front Side)</label>
                    <input type="file" id="id_card_front" name="id_card_front" class="mt-1 w-full text-sm text-gray-400 file:bg-yellow-400 file:border-0 file:text-black" required>
                    @error('id_card_front') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="id_card_back" class="text-sm font-medium text-gray-400">ID Card (Back Side)</label>
                    <input type="file" id="id_card_back" name="id_card_back" class="mt-1 w-full text-sm text-gray-400 file:bg-yellow-400 file:border-0 file:text-black" required>
                    @error('id_card_back') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="face_image" class="text-sm font-medium text-gray-400">Your Photo (Selfie)</label>
                    <input type="file" id="face_image" name="face_image" class="mt-1 w-full text-sm text-gray-400 file:bg-yellow-400 file:border-0 file:text-black" required>
                    @error('face_image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg hover:bg-yellow-500 transition-colors">Submit for Verification</button>
            </form>
        @endif
    </div>
@endsection
