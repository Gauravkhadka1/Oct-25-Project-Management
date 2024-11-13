<?php $__env->startSection('main-container'); ?>

<main>
<div class="client-page">
<?php if(session('success')): ?>
            <div id="success-message" class="alert alert-success" style="
                background-color: #d4edda;
                border: 1px solid #c3e6cb;
                color: #155724;
                padding: 10px;
                border-radius: 5px;
                margin-bottom: 15px;
                font-weight: bold;
                text-align: center;
                transition: opacity 0.5s ease;
            ">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
    <div class="client-page-heading">
        <h2>All Clients</h2>
    </div>
    <div class="clients-data">
        <table>
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Company Name</th>
                    <th>Category</th>
                    <th>Contact Person</th>
                    <th>C. Phone</th>
                    <th>C. Email</th>
                    <th>Rating</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
      // JavaScript to automatically fade out the success message
      document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0'; // Start fade out
                    setTimeout(() => {
                        successMessage.remove(); // Remove the element after fade-out
                    }, 500); // Match this duration with CSS transition time
                }, 3000); // Display time before fade-out (3 seconds)
            }
        });
</script>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/clients.blade.php ENDPATH**/ ?>