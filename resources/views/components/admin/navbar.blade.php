<nav class="flex flex-col gap-10">

    @php
        $links = [
            [
                'route' => 'admin.profile',
                'label' => 'ADMIN PROFILE',
                'icon'  => 'resources/images/desktop-mobile/Group.png'
            ],
            [
                'route' => 'admin.dashboard',
                'label' => 'DASHBOARD',
                'icon'  => 'resources/images/desktop-mobile/Vector.png'
            ],
            [
                'route' => 'admin.survey-content',
                'label' => 'SURVEY CONTENT',
                'icon'  => 'resources/images/desktop-mobile/Vector-1.png'
            ],
            [
                'route' => 'admin.user-management',
                'label' => 'USER MANAGEMENT',
                'icon'  => 'resources/images/desktop-mobile/member-list-2.png'
            ],
            [
                'route' => 'admin.report',
                'label' => 'REPORT GENERATOR',
                'icon'  => 'resources/images/desktop-mobile/newspaper-2.png'
            ],
        ];
    @endphp

    @foreach ($links as $item)
        <hr class="text-white">

        @php
            $isActive = $item['route'] !== '#' && request()->routeIs($item['route']);
        @endphp

        <div class="flex flex-row justify-center items-center gap-2">
            <div class="max-w-20 h-auto">
                <img class="max-w-20 h-auto" src="{{ Vite::asset($item['icon']) }}" alt="">
            </div>

            <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
               class="text-[20px] {{ $isActive ? 'text-[#FFEA01]' : 'text-white' }}">
               {{ $item['label'] }}
            </a>
        </div>
    @endforeach

    <hr class="text-white">

</nav>

<form method="POST" action="{{ route('admin.logout') }}" class="mx-auto mt-auto mb-5">
    @csrf
    <button
        class="rounded-full px-8 py-3 bg-linear-[180deg,#EB1400_0%,#850B00_100%]">

        <h3 class="text-white text-[20px]">LOGOUT</h3>

    </button>
</form>
