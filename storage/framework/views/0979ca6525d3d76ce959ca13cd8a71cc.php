<?php
    if (!auth()->check()) {
        return redirect()->route('login');
    }
?>

<div id="main-container" class="main-container">
      <?php echo $__env->make('frontends.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php echo $__env->yieldContent('main-container'); ?>
      <?php echo $__env->make('frontends.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/layouts/main.blade.php ENDPATH**/ ?>