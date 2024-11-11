

<?php $__env->startSection('main-container'); ?>

   <div class="add-clients-page">
   <div class="form-container">
        <h2>Add Client Details</h2>
        <form action="<?php echo e(route('clients.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
            <div class="form-group">
                <label for="company_name">Company Name:</label>
                <input type="text" id="company_name" name="company_name" nullable>
            </div>
            
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="3" nullable></textarea>
            </div>
            
            <div class="form-group">
                <label for="company_phone">Company Phone:</label>
                <input type="tel" id="company_phone" name="company_phone" nullable>
            </div>
            
            <div class="form-group">
                <label for="company_email">Company Email:</label>
                <input type="email" id="company_email" name="company_email" nullable>
            </div>
            
            <div class="form-group">
                <label for="contact_person">Contact Person:</label>
                <input type="text" id="contact_person" name="contact_person" nullable>
            </div>
            
            <div class="form-group">
                <label for="contact_person_phone">Contact Person Phone:</label>
                <input type="tel" id="contact_person_phone" name="contact_person_phone" nullable>
            </div>
            
            <div class="form-group">
                <label for="contact_person_email">Contact Person Email:</label>
                <input type="email" id="contact_person_email" name="contact_person_email" nullable>
            </div>
            
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" nullable>
                    <option value="Webiste">Webiste</option>
                    <option value="Microsoft">Microsoft</option>
                    <option value="Hosting">Hosting</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="website_status">Overall Website Rating :</label>
               <input type="string" id="website_status"  name="website_status" nullable>
            </div>
            
            <div class="form-group">
                <label for="issues">Issues:</label>
                <textarea id="issues" name="issues" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="hosting_start">Hosting Start Date:</label>
                <input type="date" id="hosting_start" name="hosting_start" nullable>
            </div>
            
            <div class="form-group">
                <label for="hosting_end">Hosting End Date:</label>
                <input type="date" id="hosting_end" name="hosting_end" nullable>
            </div>
            
            <button type="submit" class="submit-btn">Add Client</button>
        </form>
    </div>
   </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/add-clients.blade.php ENDPATH**/ ?>