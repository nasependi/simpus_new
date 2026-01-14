<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-gradient-to-br from-sky-100 via-blue-50 to-indigo-100 antialiased dark:bg-gradient-to-br dark:from-neutral-950 dark:via-neutral-900 dark:to-neutral-950">
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-4 md:p-6">
        <div class="flex w-full max-w-md flex-col gap-2">
            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>