<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'SIMPERSITE'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>  

    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="flex h-screen">
        <?php echo $__env->make('layouts.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col">
            <header class="bg-white pr-10 p-6 flex justify-end items-center border-b border-gray-300">
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="font-semibold text-sm"><?php echo e(Auth::user()->nama ?? 'Admin'); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e(Auth::user()->email ?? 'admin@gmail.com'); ?></p>
                    </div>
                    <img class="h-10 w-10 rounded-full object-cover" src="<?php echo e(Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama ?? 'Admin') . '&background=random'); ?>" alt="User Avatar">
                </div>
            </header>

            <main class="flex-1 p-4 overflow-y-auto">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\SistemSarpras\resources\views/layouts/app.blade.php ENDPATH**/ ?>