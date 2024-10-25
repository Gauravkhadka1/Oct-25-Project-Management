

<?php $__env->startSection('main-container'); ?>

    <div class="verifyemail">
        <div class="verifyemailcontent">
            <div class="verifyemailtext">
                <?php echo e(__('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.')); ?>

            </div>

            <?php if(session('status') == 'verification-link-sent'): ?>
                <div class="mb-4 font-medium text-sm text-green-600">
                    <?php echo e(__('A new verification link has been sent to the email address you provided during registration.')); ?>

                </div>
            <?php endif; ?>

            <div class="vrl">
                <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="vresend"> 
                        <button>
                            <?php echo e(__('Resend Verification Email')); ?>

                        </button>
                    </div>
                </form>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="vlogout">
                        <button type="submit" class="verify-logout">
                            <?php echo e(__('Log Out')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\new project management\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>