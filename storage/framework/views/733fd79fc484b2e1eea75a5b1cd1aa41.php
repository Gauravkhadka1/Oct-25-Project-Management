
<?php $__env->startSection('main-container'); ?>
<div class="paid-payments-page">
    
    <div class="task-board">
        <!-- Website Column -->
        <div class="task-column" id="due">
            <div class="todo-heading-payments">
                <img src="<?php echo e(url('/frontend/images/web.png')); ?>" alt="">
                <h3>Website</h3>
            </div>
            <div class="task-list">
                <?php $__currentLoopData = $payments->where('category', 'Website'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task">
                <div class="task-name">
                        <a href="<?php echo e(url ('paymentdetails/' .$payment->id)); ?>">
                            <p><?php echo e($payment->company_name); ?></p>
                        </a>
                    </div>
                    <div class="category">
                        <img src="<?php echo e(url ('/frontend/images/category.png')); ?>" alt=""> : <?php echo e($payment->category); ?>

                    </div>

                    <div class="inquiry-date">
                          <strong style="margin-right: 4px;">NPR: </strong> <?php echo e($payment->amount); ?>

                    </div>
                   
                    <div class="paid-date"> 
                        <strong>Paid on:</strong> <?php echo e($payment->paid_date); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="total-category">
                <strong><?php echo e($paidLabel); ?>: NPR <?php echo e(number_format($totalWebsite)); ?></strong>
            </div>
        </div>

        <!-- Microsoft Column -->
        <div class="task-column" id="invoice_sent">
            <div class="invoicesent-heading">
                <img src="<?php echo e(url('/frontend/images/microsoft.png')); ?>" alt="">
                <h3>Microsoft</h3>
            </div>
            <div class="task-list">
                <?php $__currentLoopData = $payments->where('category', 'Microsoft'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task">
                    <div class="task-name">
                        <a href="<?php echo e(url ('paymentdetails/' .$payment->id)); ?>">
                            <p><?php echo e($payment->company_name); ?></p>
                        </a>
                    </div>
                    <div class="category">
                        <img src="<?php echo e(url ('/frontend/images/category.png')); ?>" alt=""> : <?php echo e($payment->category); ?>

                    </div>

                    <div class="inquiry-date">
                          <strong style="margin-right: 4px;">NPR: </strong> <?php echo e($payment->amount); ?>

                    </div>
                   
                    <div class="paid-date"> 
                        <strong>Paid on:</strong> <?php echo e($payment->paid_date); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="total-category">
                <strong><?php echo e($paidLabel); ?>: NPR <?php echo e(number_format($totalMicrosoft)); ?></strong>
            </div>
        </div>

        <!-- Renewals Column -->
        <div class="task-column" id="vatbill_sent">
            <div class="vatbillsent-heading">
                <img src="<?php echo e(url('/frontend/images/renew.png')); ?>" alt="">
                <h3>Renewals</h3>
            </div>
            <div class="task-list">
                <?php $__currentLoopData = $payments->where('category', 'Renewal'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task">
                    <div class="task-name">
                        <a href="<?php echo e(url ('paymentdetails/' .$payment->id)); ?>">
                            <p><?php echo e($payment->company_name); ?></p>
                        </a>
                    </div>
                    <div class="category">
                        <img src="<?php echo e(url ('/frontend/images/category.png')); ?>" alt=""> : <?php echo e($payment->category); ?>

                    </div>

                    <div class="inquiry-date">
                          <strong style="margin-right: 4px;">NPR: </strong> <?php echo e($payment->amount); ?>

                    </div>
                   
                    <div class="paid-date"> 
                        <strong>Paid on:</strong> <?php echo e($payment->paid_date); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="total-category">
                <strong><?php echo e($paidLabel); ?>: NPR <?php echo e(number_format($totalRenewal)); ?></strong>
            </div>
        </div>

        <!-- Others Column -->
        <div class="task-column" id="paid">
            <div class="paid-heading">
                <img src="<?php echo e(url('/frontend/images/others.png')); ?>" alt="">
                <h3>Others</h3>
            </div>
            <div class="task-list">
                <?php $__currentLoopData = $payments->whereNotIn('category', ['Website', 'Microsoft', 'Renewal']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task">
                    <div class="task-name">
                        <a href="<?php echo e(url ('paymentdetails/' .$payment->id)); ?>">
                            <p><?php echo e($payment->company_name); ?></p>
                        </a>
                    </div>
                    <div class="category">
                        <img src="<?php echo e(url ('/frontend/images/category.png')); ?>" alt=""> : <?php echo e($payment->category); ?>

                    </div>

                    <div class="inquiry-date">
                          <strong style="margin-right: 4px;">NPR: </strong> <?php echo e($payment->amount); ?>

                    </div>
                   
                    <div class="paid-date"> 
                        <strong>Paid on:</strong> <?php echo e($payment->paid_date); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="total-category">
                <strong><?php echo e($paidLabel); ?>: NPR <?php echo e(number_format($totalOthers)); ?></strong>
            </div>
        </div>
    </div>

    <!-- Overall Total Paid Amount -->
    <div class="total-amounts-paid">
        <h2><?php echo e($paidLabel); ?>: NPR <?php echo e(number_format($totalPaidAmount)); ?></h2>
    </div>
</div>




<style>
  .paid-payments-page {
        margin-top: 30px;
    }
    .total-category {
        margin-top: 10px;
    }

    .todo-heading-payments {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e16b16;
    width: 95px; 
    padding: 5px;
    border-radius: 7px;
    margin-top: -7px;
    margin-bottom: 5px;
}
.todo-heading-payments img {
    width: 15px;
    margin-right: 7px;
}
.todo-heading-payments h3 {
    font-size: 14px;
}
.invoicesent-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f7b04f;
    width: 105px; 
    padding: 5px;
    border-radius: 7px;
      margin-top: -7px;
    margin-bottom: 5px;
}
.invoicesent-heading img {
    width: 15px;
    margin-right: 10px;
}
.invoicesent-heading h3 {
    font-size: 14px;
}
.vatbillsent-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #4f72f3;
    width: 107px; 
    padding: 5px;
    border-radius: 7px;
      margin-top: -7px;
    margin-bottom: 5px;
}
.vatbillsent-heading img {
    width: 15px;
    margin-right: 10px;
}
.vatbillsent-heading h3 {
   font-size: 14px;
}
.paid-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #318945;
    width: 85px; 
    padding: 5px;
    border-radius: 7px;
      margin-top: -7px;
    margin-bottom: 5px;
}
.paid-heading img {
    width: 15px;
    margin-right: 10px;
}
.paid-heading h3 {
    font-size: 14px;
}
.closed-heading-prospect {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #318945;
    width: 125px; 
    padding: 5px;
    border-radius: 7px;
      margin-top: -7px;
    margin-bottom: 5px;
}
.closed-heading-prospect img {
    width: 15px;
    margin-right: 10px;
}
.closed-heading-prospect h3 {
    font-size: 14px;
}

  

</style>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/paid-payments.blade.php ENDPATH**/ ?>