<?php echo $__env->make('frontends.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldContent('main-container'); ?>
<?php echo $__env->make('frontends.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/comeonne/management.comeonnepal.com/resources/views/frontends/layouts/main.blade.php ENDPATH**/ ?>