<?php $__env->startSection('main-container'); ?>

<div class="client-detail-page">
    <h2> <img src="<?php echo e(url('public/frontend/images/user.png')); ?>" alt="">Add New Client</h2>
    <form action="<?php echo e(route('clients.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div id="toastBox">

        </div>
        <div class="client-informations">
            <div class="contact-information">
                <div class="company-informations">
                    <div class="client-details-form">
                        <label for="comapny_name">Company Name</label>
                        <input type="text" id="company_name" name="company_name">
                    </div>
                    <div class="client-details-form">
                        <label for="domain_name">Domain</label>
                        <input type="text" id="website" name="website">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Company Phone</label>
                        <input type="text" id="company_phone" name="company_phone">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Company Email</label>
                        <input type="email" id="comapny_email" name="company_email">
                    </div>
                    <div class="client-details-form">
                        <label for="vat_no">PAN/ VAT Number</label>
                        <input type="text" id="vat_no" name="vat_no">
                    </div>
                </div>
                <div class="company-contact-person-informations">
                    <div class="client-details-form">
                        <label for="address">Contact Person </label>
                        <input type="text" id="contact_person" name="contact_person">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Contact Person Phone</label>
                        <input type="text" id="contact_person_phone" name="contact_person_phone">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Contact Person Email </label>
                        <input type="email" id="contact_person_email" name="contact_person_email">
                    </div>
                    <div class="additional-informations">
                        <div class="general-info">
                            <div class="client-details-form">
                                <label for="additional_info">Additional Informations</label>
                                <textarea id="summernote" name="additional_info"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="client-services">
            <div class="domain-service" id="website-service">
                <h2> <img src="<?php echo e(url ('public/frontend/images/design-blue.png')); ?>" alt=""> Web Design</h2>

                <div class="client-details-form">
                    <label for="choose_category">Category </label>
                    <select id="choose_subcategory" name="subcategory">
                        <option value="">Select Category</option>
                        <option value="Company Portfolio">Company Portfolio</option>
                        <option value="NGO/ INGO">NGO/ INGO</option>
                        <option value="Tourism">Tourism</option>
                        <option value="Ecommerce">Ecommerce</option>
                        <option value="Hotels/ Cafe">Hotels/ Cafe</option>
                        <option value="Education Consultancy">Education Consultancy</option>
                        <option value="Manpower">Manpower</option>
                        <option value="News Portal">News Portal</option>
                        <option value="Health">Health</option>
                        <option value="Personal Portfolio">Personal Portfolio</option>
                    </select>
                </div>
                <!-- <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="domain_amount" name="domain_amount">
                </div> -->
                <div class="installments">
                    <!-- <div class="installment-section">
                        <h3>Select Installments</h3>
                        <div class="checkbox-group">
                            <label>
                                <input type="radio" name="no_of_installments" value="2">
                                2
                            </label>
                            <label>
                                <input type="radio" name="no_of_installments" value="3">
                                3
                            </label>
                            <label>
                                <input type="radio" name="no_of_installments" value="4">
                                4
                            </label>
                        </div>
                    </div> -->

                    <div id="installment-dates" class="installment-dates">
                        <div class="first-installment-inputs">
                            <div class="first-installment">
                                <label for="">1st Inst.. Date</label>
                                <input type="date" name="first_installment">
                            </div>
                            <div class="first-installment_amount">
                                <label for="">Amount</label>
                                <input type="number" name="first_installment_amount">
                            </div>
                        </div>
                        <div class="first-installment-inputs">
                        <div class="first-installment">
                            <label for="">2nd Inst.. Date</label>
                            <input type="date" name="second_installment">
                        </div>
                        <div class="first-installment_amount">
                            <label for="">Amount</label>
                            <input type="number" name="second_installment_amount">
                        </div>
                        </div>
                        
                        <div class="first-installment-inputs">
                        <div class="first-installment">
                            <label for="">3rd Inst.. Date</label>
                            <input type="date" name="third_installment">
                        </div>
                        <div class="first-installment_amount">
                            <label for="">Amount</label>
                            <input type="number" name="third_installment_amount">
                        </div>
                        </div>
                       <div class="first-installment-inputs">
                       <div class="first-installment">
                            <label for="">Final Inst. Date</label>
                            <input type="date" name="fourth_installment">
                        </div>
                        <div class="first-installment_amount">
                            <label for="">Amount</label>
                            <input type="number" name="fourth_installment_amount">
                        </div>
                       </div>
                    </div>
                </div>
                <div class="client-details-form">
                    <label for="contract">Upload Contract</label>
                    <input type="file" id="contract" name="contract" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                </div>

            </div>
            <div class="domain-service">
                <h2> <img src="<?php echo e(url ('public/frontend/images/domain.png')); ?>" alt=""> Domain</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="domain_active_date" name="domain_active_date">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="domain_expiry_date" name="domain_expiry_date">
                </div>
                <div class="client-details-form">
                    <label for="choose_domain">Choose Domain </label>
                    <select id="choose_domain" name="domain_type">
                        <option value="">Select Domain</option>
                        <option value="Site5">Site5</option>
                        <option value="Hostforweb">Hostforweb</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="domain_amount" name="domain_amount">
                </div>
            </div>
            <div class="domain-service">
                <h2> <img src="<?php echo e(url('public/frontend/images/hosting.png')); ?>" alt=""> Hosting</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="hosting_active_date" name="hosting_active_date">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="hosting_expiry_date" name="hosting_expiry_date">
                </div>
                <div class="client-details-form">
                    <label for="hosting_space">Hosting Space</label>
                    <input type="text" id="hosting_space" name="hosting_space">
                </div>
                <div class="client-details-form">
                    <label for="choose_hosting">Choose Hosting</label>
                    <select id="choose_hosting" name="hosting_type">
                        <option value="">Select Hosting</option>
                        <option value="Site5">Site5</option>
                        <option value="Hostforweb">Hostforweb</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="hosting_amount" name="hosting_amount">
                </div>
            </div>
            <div class="domain-service">
                <h2><img src="<?php echo e(url('public/frontend/images/microsoft.png')); ?>">Microsoft</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="microsoft_active_date" name="microsoft_active_date">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="microsoft_expiry_date" name="microsoft_expiry_date">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">No of Liscense</label>
                    <input type="text" id="no_of_license" name="no_of_license">
                </div>
                <div class="client-details-form">
                    <label for="choose_hosting">Choose Type</label>
                    <select id="choose_type" name="microsoft_type">
                        <option value="">Select Type</option>
                        <option value="Paid">Paid</option>
                        <option value="Non-Profit">Non-Profit</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="microsoft_amount" name="microsoft_amount">
                </div>
            </div>
            <div class="domain-service">
                <h2> <img src="<?php echo e(url('public/frontend/images/maintenance.png')); ?>">Maintenance</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="maintenance_active_date" name="maintenance_active_date">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="maintenance_expiry_date" name="maintenance_expiry_date">
                </div>
                <div class="client-details-form">
                    <label for="choose_maintenance">Choose Package</label>
                    <select id="choose_maintenance" name="maintenance_type">
                        <option value="">Select Package</option>
                        <option value="Basic">Basic</option>
                        <option value="Advance">Advance</option>
                        <option value="Standard">Standard</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="maintenance_amount" name="maintenance_amount">
                </div>
                <div class="client-details-form">
                    <label for="maintenance_contract">Upload Contract</label>
                    <input type="file" id="maintenance_contract" name="maintenance_contract" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                </div>
            </div>
            <div class="domain-service">
                <h2> <img src="<?php echo e(url('public/frontend/images/seo.png')); ?>">SEO</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="seo_active_date" name="seo_active_date">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="seo_expiry_date" name="seo_expiry_date">
                </div>
                <div class="client-details-form">
                    <label for="choose_seo">Choose SEO </label>
                    <select id="choose_seo" name="seo_type">
                        <option value="">Select SEO</option>
                        <option value="Basic">Basic</option>
                        <option value="Advance">Advance</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="seo_amount" name="seo_amount">
                </div>
                <div class="client-details-form">
                    <label for="seo_contract">Upload SEO Contract</label>
                    <input type="file" id="seo_contract" name="seo_contract" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                </div>
            </div>
        </div>
        <div class="client-details-form">
            <button type="submit" class="btn btn-primary">Add</button>
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