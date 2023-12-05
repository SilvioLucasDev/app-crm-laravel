<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased">
    <x-toast />

    {{-- Displays only on mobile --}}
    <x-nav sticky class="lg:hidden bg-sky-800 text-white">
        <x-slot:brand>
            {{-- Drawer toggle for "main-drawer" --}}
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>

            {{-- Your logo --}}
            My App
        </x-slot:brand>
    </x-nav>

    <x-main full-width>
        <x-slot:sidebar drawer="main-drawer" collapsible class="pt-3 bg-sky-800 text-white">

            {{-- Hidden when collapsed --}}
            <div class="hidden-when-collapsed ml-5 font-black text-4xl text-yellow-500">mary</div>

            {{-- Display when collapsed --}}
            <div class="display-when-collapsed ml-5 font-black text-4xl text-orange-500">m</div>

            {{-- Custom `active menu item background color` --}}
            <x-menu activate-by-route active-bg-color="bg-base-300/10">

                {{-- User --}}
                @if ($user = auth()->user())
                    <x-list-item :item="$user" sub-value="username" no-separator no-hover
                        class="!-mx-2 mt-2 mb-5 border-y border-y-sky-900">
                        <x-slot:actions>
                            <livewire:auth.logout />
                        </x-slot:actions>
                    </x-list-item>
                @endif

                <x-menu-item title="Home" icon="o-home" link="/" />
                <x-menu-item title="Yeah" icon="o-sparkles" link="####" />

                @can(\App\Enums\Can::BE_AN_ADMIN->value)
                    <x-menu-sub title="Admin" icon="o-lock-closed">
                        <x-menu-item title="Dashboard" icon="o-chart-bar-square" :link="route('admin.dashboard')" />
                        <x-menu-item title="Users" icon="o-users" :link="route('admin.users')" />
                    </x-menu-sub>
                @endcan
            </x-menu>
        </x-slot:sidebar>

        <x-slot:content>
            @if (session('impersonate'))
                {{ trans("You're impersonating :name, click here to stop the impersonation.", ['name' => auth()->user()->name]) }}
            @endif
            {{ $slot }}
        </x-slot:content>
    </x-main>
</body>

</html>
