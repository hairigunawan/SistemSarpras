<?php $__env->startComponent('mail::message'); ?>
# Verifikasi Email - SIMPERSITE

Halo <?php echo new \Illuminate\Support\EncodedHtmlString($user->nama); ?>,

Terima kasih telah mendaftar di SIMPERSITE. Untuk melanjutkan proses registrasi, silakan verifikasi email Anda dengan memasukkan kode verifikasi berikut:

**Kode Verifikasi:** <?php echo new \Illuminate\Support\EncodedHtmlString($verificationCode); ?>


Kode verifikasi ini akan berlaku selama 24 jam. Jika Anda tidak melakukan permintaan ini, silakan abaikan email ini.

Jika Anda memiliki pertanyaan, silakan hubungi tim kami.

Salam hangat,
Tim SIMPERSITE

<?php $__env->startComponent('mail::button', ['url' => route('verification.form'), 'color' => 'blue']); ?>
Verifikasi Email
<?php echo $__env->renderComponent(); ?>

<?php $__env->startComponent('mail::subcopy'); ?>
Jika tombol di atas tidak berfungsi, silakan salin dan tempel URL berikut ke browser Anda: <?php echo new \Illuminate\Support\EncodedHtmlString(route('verification.form')); ?>

<?php echo $__env->renderComponent(); ?>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/emails/verification.blade.php ENDPATH**/ ?>