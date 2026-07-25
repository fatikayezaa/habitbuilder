<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'HabitBuilder' }}</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased overflow-x-hidden">

    <div class="flex min-h-screen">

        <x-sidebar />

        <main class="flex-1 flex flex-col">

            <x-navbar />

            <section class="flex-1 p-8">
                {{ $slot }}
            </section>

        </main>

    </div>

</body>
</html>