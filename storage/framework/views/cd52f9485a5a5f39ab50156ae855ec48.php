<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Verifikasi OTP</h2>

    <?php if(session('status')): ?>
        <div class="text-green-600 mb-2"><?php echo e(session('status')); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('password.verifyOtp')); ?>">
        <?php echo csrf_field(); ?>

        <label class="block">Kode OTP</label>
        <input type="text" name="otp" class="border rounded w-full p-2" required>

        <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-red-600"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <button class="bg-purple-600 text-white px-4 py-2 rounded mt-4 w-full">
            Verifikasi
        </button>
    </form>
</div>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\SistemSarpras\resources\views/auth/verify-otp.blade.php ENDPATH**/ ?>