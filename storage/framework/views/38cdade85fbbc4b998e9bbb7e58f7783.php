<?php $__env->startSection('main-container'); ?>

<div class="payment-detail-page">
    <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="payment-detail">
        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="comapny-name">
            <p><?php echo e($payment->company_name); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="add-payment-task">
            <button>
            <h2>Add task</h2>
            </button>
            
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="payment-comments">
        <h2>Comments</h2>
        <div id="view-activities-modal" class="modal">
    <div class="modal-content">
        <h3>Activities</h3>
        <div id="activities-list">
            <!-- Activities will be populated here -->
        </div>

        <!-- Sticky Add Activity Section -->
        <div id="add-activity-section" class="sticky-section">
            <form id="add-activity-form" action="<?php echo e(route('payments-activities.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="payments_id" id="activity-payments-id">
                <input type="text" name="details" id="activity-details" placeholder="Add Comments..." required>
                <div id="suggestions"></div>
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">Add<div id="loading-spinner" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
                    <img src="<?php echo e(url('frontend/images/spinner.gif')); ?>" alt="Loading...">
                </div>
                </button>
                </div>
            </form>
        </div>

        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeViewActivitiesModal()">Close</button>
        </div>
    </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/payment-details.blade.php ENDPATH**/ ?>