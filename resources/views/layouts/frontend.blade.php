<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings['site_name'] ?? 'CodeShack')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Phosphor Icons CDN -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #111827; }
        .text-gradient { background: linear-gradient(to right, #fde047, #facc15); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 10px; }
        .marquee-container { overflow: hidden; position: relative; width: 100%; }
        .marquee-content { display: inline-block; white-space: nowrap; animation: marquee-scroll 20s linear infinite; }
        .marquee-content span { margin-right: 50px; }
        @keyframes marquee-scroll { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }

        .fake-transaction-popup {
            position: fixed; bottom: 80px; left: 1rem; background-color: rgba(30, 41, 59, 0.9);
            backdrop-filter: blur(5px); color: #f1f5f9; padding: 0.75rem 1rem; border-radius: 8px;
            border: 1px solid #334155; box-shadow: 0 4px 12px rgba(0,0,0,0.3); display: flex;
            align-items: center; gap: 0.75rem; z-index: 2000; opacity: 0;
            transform: translateY(20px); transition: opacity 0.5s, transform 0.5s;
        }
        .fake-transaction-popup.show { opacity: 1; transform: translateY(0); }

        /* Floating WhatsApp Button Styles */
        .floating-whatsapp-btn {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background-color: #25D366;
            color: white;
            width: 56px;
            height: 56px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 1000;
            transition: transform 0.2s ease-in-out;
        }
        .floating-whatsapp-btn:hover {
            transform: scale(1.1);
        }
    </style>
    @stack('styles')
    {!! $settings['header_scripts'] ?? '' !!}
</head>
<body class="flex items-center justify-center min-h-screen">

    <!-- App Container -->
    <div class="w-full max-w-sm mx-auto bg-[#10111A] text-white shadow-2xl rounded-3xl overflow-hidden relative">
        <div class="h-[800px] overflow-y-auto">

            <!-- Header -->
            <header class="flex items-center justify-between p-4 bg-[#10111A] sticky top-0 z-20">
                <i class="ph ph-circles-four text-2xl text-yellow-400"></i>
                <h1 class="text-lg font-bold">{{ $settings['site_name'] ?? 'CodeShack' }}</h1>
                <div class="flex items-center space-x-4">
                    <i id="bell-button" class="ph ph-bell text-2xl cursor-pointer"></i>
                    <i id="menu-button" class="ph ph-list text-2xl cursor-pointer"></i>
                </div>
            </header>

            <!-- Scrolling Announcement Bar -->
            @if(isset($latestAnnouncement))
            <div class="bg-yellow-400/10 border-y border-yellow-400/30 text-yellow-300 p-2 text-sm font-semibold marquee-container">
                <div class="marquee-content">
                    <span><i class="ph-fill ph-megaphone"></i> {{ $latestAnnouncement->title }}: {{ $latestAnnouncement->content }}</span>
                </div>
            </div>
            @endif

            <!-- Main Content -->
            <main class="p-4 space-y-6">
                @yield('content')
            </main>
        </div>

        <!-- Bottom Navigation -->
        @include('layouts.partials.frontend-bottom-nav')
    </div>

    <!-- Side Menu (Drawer) -->
    <div id="side-menu-container" class="fixed inset-0 z-30 hidden">
        <div id="menu-overlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div id="menu-drawer" class="relative w-72 h-full bg-[#14151c] shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out p-6 text-gray-300">
            @include('layouts.partials.frontend-sidebar')
        </div>
    </div>

    <!-- Fake Transaction Popup -->
    <div id="fake-transaction-popup" class="fake-transaction-popup">
        <i class="ph ph-arrow-circle-up text-xl text-red-400"></i>
        <div>
            <p class="text-xs"><strong id="fake-name"></strong> just withdrew</p>
            <p class="font-bold text-sm" id="fake-amount"></p>
        </div>
    </div>

    <!-- Announcement Modal -->
    <div id="announcement-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
        <div class="bg-[#1E1F2B] rounded-2xl p-6 w-full max-w-sm text-center space-y-4 relative">
            <button id="close-announcement-modal" class="absolute top-2 right-2 text-gray-500 hover:text-white text-2xl">&times;</button>
            <i class="ph ph-megaphone text-5xl text-yellow-400"></i>
            @if(isset($latestAnnouncement))
                <h3 class="font-bold text-lg">{{ $latestAnnouncement->title }}</h3>
                <p class="text-sm text-gray-300">{{ $latestAnnouncement->content }}</p>
            @else
                <h3 class="font-bold text-lg">No Announcements</h3>
                <p class="text-sm text-gray-300">There are no new announcements at the moment.</p>
            @endif
        </div>
    </div>

    <!-- Floating WhatsApp Button -->
    @if(!empty($settings['whatsapp_number']))
        <a href="https://wa.me/{{ $settings['whatsapp_number'] }}" target="_blank" class="floating-whatsapp-btn">
            <i class="ph-bold ph-whatsapp-logo text-3xl"></i>
        </a>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Side Menu Logic ---
        const menuButton = document.getElementById('menu-button');
        const menuContainer = document.getElementById('side-menu-container');
        const menuDrawer = document.getElementById('menu-drawer');
        const menuOverlay = document.getElementById('menu-overlay');

        const openMenu = () => {
            menuContainer.classList.remove('hidden');
            setTimeout(() => {
                menuDrawer.classList.remove('-translate-x-full');
                const closeMenuButton = document.getElementById('close-menu-button');
                if(closeMenuButton) {
                    closeMenuButton.addEventListener('click', closeMenu);
                }
            }, 10);
        };

        const closeMenu = () => {
            menuDrawer.classList.add('-translate-x-full');
            setTimeout(() => {
                menuContainer.classList.add('hidden');
            }, 300);
        };

        if(menuButton) menuButton.addEventListener('click', openMenu);
        if(menuOverlay) menuOverlay.addEventListener('click', closeMenu);

        // --- Announcement Modal Logic ---
        const bellButton = document.getElementById('bell-button');
        const announcementModal = document.getElementById('announcement-modal');
        const closeAnnouncementModal = document.getElementById('close-announcement-modal');

        if (bellButton) {
            bellButton.addEventListener('click', () => {
                announcementModal.classList.remove('hidden');
            });
        }

        if (closeAnnouncementModal) {
            closeAnnouncementModal.addEventListener('click', () => {
                announcementModal.classList.add('hidden');
            });
        }

        // --- Fake Transaction Popup Logic ---
        const popup = document.getElementById('fake-transaction-popup');
        const nameEl = document.getElementById('fake-name');
        const amountEl = document.getElementById('fake-amount');

        const fakeNames = [
                'John S.', 'Maria P.', 'Ahmed K.', 'Li W.', 'Fatima Z.', 'David L.', 'Emily R.', 'Omar T.', 'Sophia D.', 'James C.',
                'Nina H.', 'Hassan B.', 'Linda M.', 'Chen Y.', 'Isla F.', 'Mohammed A.', 'Ava K.', 'Ali R.', 'Emma J.', 'Noah E.',
                'Zara Q.', 'Lucas V.', 'Lina N.', 'Daniel S.', 'Amna T.', 'Ethan W.', 'Layla G.', 'Adam M.', 'Hiba L.', 'Jacob T.',
                'Mei Z.', 'Sarah B.', 'Yusuf I.', 'Olivia F.', 'Mia R.', 'Ibrahim N.', 'Aaliyah D.', 'Liam H.', 'Tariq S.', 'Chloe V.',
                'Rayan M.', 'Huda C.', 'Isaac J.', 'Jana P.', 'Bilal O.', 'Hannah K.', 'Tanya S.', 'Oscar L.', 'Noura Y.', 'Leo B.',
                'Aisha T.', 'Aaron Q.', 'Elena R.', 'Rehan A.', 'Freya G.', 'Zaid W.', 'Selina K.', 'Malik D.', 'Dania L.', 'Kareem N.',
                'Lara J.', 'Jibran H.', 'Victoria M.', 'Arham Z.', 'Ruby F.', 'Samir T.', 'Clara Y.', 'Imran C.', 'Ivy N.', 'Rayyan P.',
                'Ella A.', 'Farhan B.', 'Lily V.', 'Nashit K.', 'Maya S.', 'Hasan Q.', 'Leila O.', 'Zayan M.', 'Julia H.', 'Usman T.',
                'Bianca R.', 'Taimoor L.', 'Amelia D.', 'Haroon S.', 'Grace Y.', 'Zohair C.', 'Stella J.', 'Adeel M.', 'Yasmin W.',
                'Elias G.', 'Sana P.', 'Hamza Z.', 'Florence T.', 'Asad K.', 'Naomi L.', 'Areeb B.', 'Daisy F.', 'Saad N.', 'Heidi M.',
                'Faizan Q.', 'Poppy R.', 'Noman Y.', 'Nadia S.'
                ];

        function showFakeTransaction() {
            const randomName = fakeNames[Math.floor(Math.random() * fakeNames.length)];
            const randomAmount = (Math.random() * (500 - 50) + 50).toFixed(2);

            nameEl.textContent = randomName;
            amountEl.textContent = `$${randomAmount}`;

            popup.classList.add('show');

            setTimeout(() => {
                popup.classList.remove('show');
            }, 4000);
        }

        setInterval(showFakeTransaction, 8000);
    });
    </script>
    @stack('scripts')
</body>
</html>
