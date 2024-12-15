<?php $__env->startSection('main-container'); ?>

<div class="client-detail-page">
    <form action="<?php echo e(route('clients.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div id="toastBox">

        </div>
        <div class="client-informations">
            <h2>Add New Clients</h2>
            <div class="contact-information">
                <div class="client-details-form">
                    <label for="comapny_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name" >
                </div>
                <div class="client-details-form">
                    <label for="domain_name">Domain</label>
                    <input type="text" id="website" name="website" >
                </div>
                <div class="client-details-form">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" >
                </div>
                <div class="client-details-form">
                    <label for="address">Company Phone</label>
                    <input type="text" id="comapny_phone" name="company_phone" >
                </div>
                <div class="client-details-form">
                    <label for="address">Company Email</label>
                    <input type="email" id="comapny_email" name="company_email" >
                </div>
                <div class="client-details-form">
                    <label for="address">Contact Person </label>
                    <input type="text" id="contact_person" name="contact_person" >
                </div>
                <div class="client-details-form">
                    <label for="address">Contact Person Phone</label>
                    <input type="text" id="contact_person_phone" name="contact_person_phone" >
                </div>
                <div class="client-details-form">
                    <label for="address">Contact Person Email </label>
                    <input type="email" id="contact_person_email" name="contact_person_email" >
                </div>
                <div class="client-details-form">
                    <label for="contract">Upload Contract (PDF)</label>
                    <input type="file" id="contract" name="contract" accept="application/pdf">
                </div>
            </div>
            <div class="general-info">
                <p></p>
            </div>
        </div>
        <div class="client-services">
            <div class="domain-service">
                <h2>Domain</h2>
                <!-- <div class="client-details-form">
                    <label for="domain_name">Domain Name</label>
                    <input type="text" id="domain_name" name="domain_name" >
                </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="domain_active_date" name="domain_active_date" placeholder="Select Domain Active Date" >
                </div>

                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="domain_expiry_date" name="domain_expiry_date" placeholder="Select Domain Expiry Date" >
                </div>
                <div class="client-details-form">
                    <label for="choose_hosting">Choose Hosting</label>
                    <select id="choose_hosting" name="hosting_type" >
                        <option value="">Select</option>
                        <option value="Site5">Site5</option>
                        <option value="Hostforweb<">Hostforweb</option>
                    </select>
                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="domain_amount" name="domain_amount" value="" >
                </div>
            </div>
            <div class="domain-service">
                <h2>Hosting</h2>
                <!-- <div class="client-details-form">
                    <label for="domain_name">Domain Name</label>
                    <input type="text" id="domain_name" name="domain_name" >
                </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="hosting_active_date" name="hosting_active_date" >
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="hosting_expiry_date" name="hosting_expiry_date" >
                </div>
                <div class="client-details-form">
                    <label for="choose_hosting">Choose Hosting</label>
                    <select id="choose_hosting" name="hosting_type" >
                        <option value="">Select</option>
                        <option value="Site5">Site5</option>
                        <option value="Hostforweb">Hostforweb</option>
                    </select>
                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="hosting_amount" name="hosting_amount" value="" >
                </div>
            </div>
            <div class="domain-service">
                <h2>Microsoft</h2>
                <!-- <div class="client-details-form">
                    <label for="domain_name">Domain Name</label>
                    <input type="text" id="domain_name" name="domain_name" >
                </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="microsoft_active_date" name="microsoft_active_date" >
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="microsoft_expiry_date" name="microsoft_expiry_date" >
                </div>

                <div class="client-details-form">
                    <label for="choose_hosting">Choose Type</label>
                    <select id="choose_hosting" name="microsoft_type" >
                        <option value="">Select</option>
                        <option value="Paid">Paid</option>
                        <option value="Non-Profit">Non-Profit</option>
                    </select>
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">No of Liscense</label>
                    <input type="text" id="no_of_license" name="no_of_license" >
                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="microsoft_amount" name="microsoft_amount" value="" >
                </div>
            </div>
            <div class="domain-service">
                <h2>Maintenance</h2>
                <!-- <div class="client-details-form">
                    <label for="domain_name">Domain Name</label>
                    <input type="text" id="domain_name" name="domain_name" >
                </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="maintenance_active_date" name="maintenance_active_date" >
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="maintenance_expiry_date" name="maintenance_expiry_date" >
                </div>
                <div class="client-details-form">
                    <label for="choose_hosting">Choose Package</label>
                    <select id="choose_hosting" name="maintenance_type" >
                        <option value="">Select</option>
                        <option value="Basic">Basic</option>
                        <option value="Advance">Advance</option>
                        <option value="Standard">Standard</option>
                    </select>
                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="maintenance_amount" name="maintenance_amount" value="" >
                </div>
            </div>
            <div class="domain-service">
                <h2>SEO</h2>
                <!-- <div class="client-details-form">
                    <label for="domain_name">Domain Name</label>
                    <input type="text" id="domain_name" name="domain_name" >
                </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="seo_active_date" name="seo_active_date" >
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="seo_expiry_date" name="seo_expiry_date" >
                </div>
                <div class="client-details-form">
                    <label for="choose_hosting">Choose SEO </label>
                    <select id="choose_hosting" name="seo_type" >
                        <option value="">Select</option>
                        <option value="Basic">Basic</option>
                        <option value="Advance">Advance</option>
                    </select>
                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="seo_amount" name="seo_amount" >
                </div>
            </div>
        </div>
        <div class="client-details-form">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Apply Flatpickr to all input[type="date"]
        flatpickr('input[type="date"]', {
            dateFormat: "Y-m-d", // Customize the format as needed
            allowInput: true // Allow manual typing
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/add-clients.blade.php ENDPATH**/ ?>