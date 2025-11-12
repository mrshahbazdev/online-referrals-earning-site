@section('content')
    <div class="space-y-6">
        <div class="flex flex-col items-center text-center">
            <div class="bg-[#1E1F2B] p-4 rounded-full">
               <i class="ph-bold ph-info text-5xl text-yellow-400"></i>
            </div>
            <h2 class="text-2xl font-bold mt-4">About {{ $settings['site_name'] ?? 'Us' }}</h2>
            <p class="text-sm text-gray-400 mt-2">Your trusted partner in the world of cryptocurrency and quantitative trading.</p>
        </div>

        <div class="bg-[#1E1F2B] p-4 rounded-lg space-y-4 text-gray-300 text-sm prose">
            {{-- Database se aane wala dynamic content --}}
            {!! $settings['about_us_content'] ?? 'No content has been set for the About Us page yet.' !!}
        </div>

        <div class="text-center">
            <h3 class="font-semibold text-lg">Follow Us</h3>
            <div class="flex justify-center gap-4 mt-4">
                <a href="#" class="w-12 h-12 bg-[#1E1F2B] rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors"><i class="ph-bold ph-telegram-logo text-2xl"></i></a>
                <a href="#" class="w-12 h-12 bg-[#1E1F2B] rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors"><i class="ph-bold ph-twitter-logo text-2xl"></i></a>
                <a href="#" class="w-12 h-12 bg-[#1E1F2B] rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors"><i class="ph-bold ph-youtube-logo text-2xl"></i></a>
            </div>
        </div>

         <div class="text-center text-xs text-gray-500 pt-4">
            <p>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? config('app.name') }}. All Rights Reserved.</p>
            <p>Version 1.0.0</p>
        </div>
    </div>
@endsection
