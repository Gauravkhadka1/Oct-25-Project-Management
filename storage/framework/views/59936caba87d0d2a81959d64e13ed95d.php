

<?php $__env->startSection('main-container'); ?>

<div class="client-detail-page">
    <h2> <img src="<?php echo e(url('public/frontend/images/user.png')); ?>" alt="">Client Info</h2>
    <form action="<?php echo e(route('client.update', ['id' => $client->id])); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div id="toastBox">

        </div>
        <div class="client-informations">
            <div class="contact-information">
                <div class="company-informations">
                    <div class="client-details-form">
                        <label for="comapny_name">Company Name</label>
                        <input type="text" id="company_name" name="company_name" value="<?php echo e($client->company_name); ?>">
                    </div>
                    <div class="client-details-form">
                        <label for="domain_name">Domain</label>
                        <input type="text" id="website" name="website" value="<?php echo e($client->website); ?>">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="<?php echo e($client->address); ?>">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Company Phone</label>
                        <input type="text" id="company_phone" name="company_phone" value="<?php echo e($client->company_phone); ?>">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Company Email</label>
                        <input type="email" id="comapny_email" name="company_email" value="<?php echo e($client->company_email); ?>">
                    </div>
                    <div class="client-details-form">
                        <label for="vat_no">PAN/ VAT Number</label>
                        <input type="text" id="vat_no" name="vat_no" value="<?php echo e($client->vat_no); ?>">
                    </div>
                </div>
                <div class="company-contact-person-informations">
                    <div class="client-details-form">
                        <label for="address">Contact Person </label>
                        <input type="text" id="contact_person" name="contact_person" value="<?php echo e($client->contact_person); ?>">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Contact Person Phone</label>
                        <input type="text" id="contact_person_phone" name="contact_person_phone" value="<?php echo e($client->contact_person_phone); ?>">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Contact Person Email </label>
                        <input type="email" id="contact_person_email" name="contact_person_email" value="<?php echo e($client->contact_person_email); ?>">
                    </div>
                    <div class="additional-informations">
                        <div class="general-info">
                            <div class="client-details-form">
                                <label for="additional_info">Additional Informations</label>
                                <textarea id="summernote" name="additional_info"><?php echo e($client->additional_info); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="payment-history" style="background-color: blue; color:white;">
                        Payment History
                    </div>
                </div>
            </div>
            <div class="client-activities">
                <h3>Activities</h3>
                <?php if($activities->isNotEmpty()): ?>
                <ul class="activities-list">
                    <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="activity-item">
                        <div class="activity-header">
                            <div class="user-info">
                                <?php if($activity->user && $activity->user->profilepic): ?>
                                <!-- Use Storage::url() to get the full URL of the profile picture -->
                                <img src="<?php echo e(Storage::url('profilepics/' . $activity->user->profilepic)); ?>" alt="Profile Picture" class="user-avatar">
                                <?php else: ?>
                                <!-- Fallback image if no profile picture is set -->
                                <img src="<?php echo e(asset('default-avatar.png')); ?>" alt="Profile Picture" class="user-avatar">
                                <?php endif; ?>
                                <strong class="user-name"><?php echo e($activity->user ? $activity->user->username : 'Unknown User'); ?></strong>
                            </div>
                            <span class="activity-time"><?php echo e($activity->created_at->format('d M Y, h:i A')); ?></span>
                        </div>
                        <p class="activity-description"><?php echo e($activity->activity); ?></p>
                        <?php if($activity->field): ?>
                        <!-- <div class="activity-changes">
                            <strong>Changed:</strong> <?php echo e($activity->field); ?> <br>
                            <strong>Old Value:</strong> <?php echo e($activity->old_value); ?> <br>
                            <strong>New Value:</strong> <?php echo e($activity->new_value); ?>

                        </div> -->
                        <?php endif; ?>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php else: ?>
                <p class="no-activities">No activities found.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="client-services">
            <div class="domain-service">
                <h2> <img src="<?php echo e(url ('public/frontend/images/design-blue.png')); ?>" alt=""> Web Design</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <!-- <div class="client-details-form">
                <label for="domain_active_date">Active Date</label>
                <input type="date" id="domain_active_date" name="domain_active_date" value="<?php echo e($client->domain_active_date); ?>">
            </div>
            <div class="client-details-form">
                <label for="domain_active_date">Expiry Date</label>
                <input type="date" id="domain_expiry_date" name="domain_expiry_date" value="<?php echo e($client->domain_expiry_date); ?>">
            </div> -->
                <div class="client-details-form">
                    <label for="choose_category">Category </label>
                    <select id="choose_subcategory" name="subcategory">
                        <option value="">Select Category</option>
                        <option value="Company Portfolio" <?php echo e($client->subcategory == 'Company Portfolio' ? 'selected' : ''); ?>>Company Portfolio</option>
                        <option value="NGO/ INGO" <?php echo e($client->subcategory == 'NGO/ INGO' ? 'selected' : ''); ?>>NGO/ INGO</option>
                        <option value="Tourism" <?php echo e($client->subcategory == 'Tourism' ? 'selected' : ''); ?>>Tourism</option>
                        <option value="Ecommerce" <?php echo e($client->subcategory == 'Ecommerce' ? 'selected' : ''); ?>>Ecommerce</option>
                        <option value="Hotels/ Cafe" <?php echo e($client->subcategory == 'Hotels/ Cafe' ? 'selected' : ''); ?>>Hotels/ Cafe</option>
                        <option value="Education Consultancy" <?php echo e($client->subcategory == 'Education Consultancy' ? 'selected' : ''); ?>>Education Consultancy</option>
                        <option value="Manpower" <?php echo e($client->subcategory == 'Manpower' ? 'selected' : ''); ?>>Manpower</option>
                        <option value="News Portal" <?php echo e($client->subcategory == 'News Portal' ? 'selected' : ''); ?>>News Portal</option>
                        <option value="Health" <?php echo e($client->subcategory == 'Health' ? 'selected' : ''); ?>>Health</option>
                        <option value="Personal Portfolio" <?php echo e($client->subcategory == 'Personal Portfolio' ? 'selected' : ''); ?>>Personal Portfolio</option>
                    </select>
                </div>
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
                        <div class="1st_installment">
                            <label for="">1st installment date</label>
                            <input type="date" name="first_installment" value="<?php echo e($client->first_installment); ?>">
                        </div>
                        <div class="1st_installment">
                        <label for="">2nd installment date</label>
                            <input type="date" name="second_installment" value="<?php echo e($client->second_installment); ?>">
                        </div>
                        <div class="1st_installment">
                        <label for="">3rd installment date</label>
                            <input type="date" name="third_installment" value="<?php echo e($client->third_installment); ?>">
                        </div>
                        <div class="1st_installment">
                        <label for="">4th installment date</label>
                            <input type="date" name="fourth_installment" value="<?php echo e($client->fourth_installment); ?>">
                        </div>
                    </div>
                </div>
                <!-- <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="domain_amount" name="domain_amount" value="<?php echo e($client->domain_amount); ?>">
                </div> -->
                <div class="client-details-form">
                    <label for="contract">Upload Contract</label>
                    <input type="file" id="contract" name="contract" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                </div>


                <div class="client-details-form">
                    <label>View Contract</label>
                    <?php if($client->contract): ?>
                    <div class="contract-preview">
                        <?php
                        $filePath = Storage::url($client->contract);
                        $fileName = basename($filePath);
                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                        ?>

                        <?php if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <!-- Image Preview -->
                        <a href="<?php echo e($filePath); ?>" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="<?php echo e($filePath); ?>" alt="Contract Image">
                            </div>
                            <!-- <p><?php echo e($fileName); ?></p>  -->

                        </a>
                        <?php elseif(in_array($fileExtension, ['pdf'])): ?>
                        <!-- PDF Icon -->
                        <a href="<?php echo e($filePath); ?>" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="<?php echo e(url('public/frontend/images/pdf.png')); ?>" alt="PDF Icon">
                            </div>
                            <!-- <p style="color:red;"><?php echo e($fileName); ?></p>  -->
                        </a>
                        <?php elseif(in_array($fileExtension, ['doc', 'docx'])): ?>
                        <!-- Word Icon -->
                        <a href="<?php echo e($filePath); ?>" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="<?php echo e(url('public/frontend/images/word.png')); ?>" alt="Word Icon">
                            </div>
                            <!-- <p><?php echo e($fileName); ?></p>  -->
                        </a>
                        <?php else: ?>
                        <p>Unsupported file type. <a href="<?php echo e($filePath); ?>" target="_blank">Download</a></p>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <p>No contract uploaded.</p>
                    <?php endif; ?>
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
                    <input type="date" id="domain_active_date" name="domain_active_date" value="<?php echo e($client->domain_active_date); ?>">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="domain_expiry_date" name="domain_expiry_date" value="<?php echo e($client->domain_expiry_date); ?>">
                </div>
                <div class="client-details-form">
                    <label for="choose_domain">Choose Domain </label>
                    <select id="choose_domain" name="domain_type">
                        <option value="">Select Domain</option>
                        <option value="domain1" <?php echo e($client->domain_type == 'domain1' ? 'selected' : ''); ?>>Domain1</option>
                        <option value="domain2" <?php echo e($client->domain_type == 'domain2' ? 'selected' : ''); ?>>Domain2</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="domain_amount" name="domain_amount" value="<?php echo e($client->domain_amount); ?>">
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
                    <input type="date" id="hosting_active_date" name="hosting_active_date" value="<?php echo e($client->hosting_active_date); ?>">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="hosting_expiry_date" name="hosting_expiry_date" value="<?php echo e($client->hosting_expiry_date); ?>">
                </div>
                <div class="client-details-form">
                    <label for="hosting_space">Hosting Space</label>
                    <input type="text" id="hosting_space" name="hosting_space" value="<?php echo e($client->hosting_space); ?>">
                </div>
                <div class="client-details-form">
                    <label for="choose_hosting">Choose Hosting</label>
                    <select id="choose_hosting" name="hosting_type">
                        <option value="">Select Hosting</option>
                        <option value="Site5" <?php echo e($client->hosting_type == 'Site5' ? 'selected' : ''); ?>>Site5</option>
                        <option value="Hostforweb" <?php echo e($client->hosting_type == 'Hostforweb' ? 'selected' : ''); ?>>Hostforweb</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="hosting_amount" name="hosting_amount" value="<?php echo e($client->hosting_amount); ?>">
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
                    <input type="date" id="microsoft_active_date" name="microsoft_active_date" value="<?php echo e($client->microsoft_active_date); ?>">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="microsoft_expiry_date" name="microsoft_expiry_date" value="<?php echo e($client->microsoft_expiry_date); ?>">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">No of Liscense</label>
                    <input type="text" id="no_of_license" name="no_of_license" value="<?php echo e($client->no_of_license); ?>">
                </div>
                <div class="client-details-form">
                    <label for="choose_hosting">Choose Type</label>
                    <select id="choose_type" name="microsoft_type">
                        <option value="">Select Type</option>
                        <option value="Paid" <?php echo e($client->microsoft_type == 'Paid' ? 'selected' : ''); ?>>Paid</option>
                        <option value="Non-Profit" <?php echo e($client->microsoft_type == 'Non-Profit' ? 'selected' : ''); ?>>Non-Profit</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="microsoft_amount" name="microsoft_amount" value="<?php echo e($client->microsoft_amount); ?>">
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
                    <input type="date" id="maintenance_active_date" name="maintenance_active_date" value="<?php echo e($client->maintenance_active_date); ?>">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="maintenance_expiry_date" name="maintenance_expiry_date" value="<?php echo e($client->maintenance_expiry_date); ?>">
                </div>
                <div class="client-details-form">
                    <label for="choose_maintenance">Choose Package</label>
                    <select id="choose_maintenance" name="maintenance_type">
                        <option value="">Select Package</option>
                        <option value="Basic" <?php echo e($client->maintenance_type == 'Basic' ? 'selected' : ''); ?>>Basic</option>
                        <option value="Advance" <?php echo e($client->maintenance_type == 'Advance' ? 'selected' : ''); ?>>Advance</option>
                        <option value="Standard" <?php echo e($client->maintenance_type == 'Standard' ? 'selected' : ''); ?>>Standard</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="maintenance_amount" name="maintenance_amount" value="<?php echo e($client->maintenance_amount); ?>">
                </div>
                <div class="client-details-form">
                    <label for="maintenance_contract">Upload Contract</label>
                    <input type="file" id="maintenance_contract" name="maintenance_contract" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                </div>

                <div class="client-details-form">
                    <label>View Maintenance Contract</label>
                    <?php if($client->maintenance_contract): ?>
                    <div class="contract-preview">
                        <?php
                        $filePath = Storage::url($client->maintenance_contract);
                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                        ?>

                        <?php if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <!-- Image Preview -->
                        <a href="<?php echo e($filePath); ?>" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="<?php echo e($filePath); ?>" alt="Maintenance Contract Image">
                            </div>
                        </a>
                        <?php elseif(in_array($fileExtension, ['pdf'])): ?>
                        <!-- PDF Icon -->
                        <a href="<?php echo e($filePath); ?>" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="<?php echo e(url('public/frontend/images/pdf.png')); ?>" alt="PDF Icon">
                            </div>
                        </a>
                        <?php elseif(in_array($fileExtension, ['doc', 'docx'])): ?>
                        <!-- Word Icon -->
                        <a href="<?php echo e($filePath); ?>" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="<?php echo e(url('public/frontend/images/word.png')); ?>" alt="Word Icon">
                            </div>
                        </a>
                        <?php else: ?>
                        <p>Unsupported file type. <a href="<?php echo e($filePath); ?>" target="_blank">Download</a></p>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <p>No contract uploaded.</p>
                    <?php endif; ?>
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
                    <input type="date" id="seo_active_date" name="seo_active_date" value="<?php echo e($client->seo_active_date); ?>">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="seo_expiry_date" name="seo_expiry_date" value="<?php echo e($client->seo_expiry_date); ?>">
                </div>
                <div class="client-details-form">
                    <label for="choose_seo">Choose SEO </label>
                    <select id="choose_seo" name="seo_type">
                        <option value="">Select SEO</option>
                        <option value="Basic" <?php echo e($client->seo_type == 'Basic' ? 'selected' : ''); ?>>Basic</option>
                        <option value="Advance" <?php echo e($client->seo_type == 'Advance' ? 'selected' : ''); ?>>Advance</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="seo_amount" name="seo_amount" value="<?php echo e($client->seo_amount); ?>">
                </div>
                <div class="client-details-form">
                    <label for="seo_contract">Upload SEO Contract</label>
                    <input type="file" id="seo_contract" name="seo_contract" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                </div>

                <div class="client-details-form">
                    <label>View SEO Contract</label>
                    <?php if($client->seo_contract): ?>
                    <div class="contract-preview">
                        <?php
                        $filePath = Storage::url($client->seo_contract);
                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                        ?>

                        <?php if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <!-- Image Preview -->
                        <a href="<?php echo e($filePath); ?>" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="<?php echo e($filePath); ?>" alt="SEO Contract Image">
                            </div>
                        </a>
                        <?php elseif(in_array($fileExtension, ['pdf'])): ?>
                        <!-- PDF Icon -->
                        <a href="<?php echo e($filePath); ?>" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="<?php echo e(url('public/frontend/images/pdf.png')); ?>" alt="PDF Icon">
                            </div>
                        </a>
                        <?php elseif(in_array($fileExtension, ['doc', 'docx'])): ?>
                        <!-- Word Icon -->
                        <a href="<?php echo e($filePath); ?>" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="<?php echo e(url('public/frontend/images/word.png')); ?>" alt="Word Icon">
                            </div>
                        </a>
                        <?php else: ?>
                        <p>Unsupported file type. <a href="<?php echo e($filePath); ?>" target="_blank">Download</a></p>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <p>No SEO contract uploaded.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="client-details-form">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>


<!-- Include jQuery (required for Summernote) -->
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

<script>
    let toastBox = document.getElementById('toastBox');
    let successMsg = ' <i class="fa-solid fa-circle-check"></i> Client Detail Updated Succesfully';
    let errorMsg = ' <i class="fa-solid fa-circle-xmark"></i> Please fix the error';
    let invalidMsg = '<i class="fa-solid fa-circle-exclamation"></i> Invalid input, check again';

    function showToast(msg) {
        let toast = document.createElement('div');
        toast.classList.add('toast');
        toast.innerHTML = msg;
        toastBox.appendChild(toast);

        if (msg.includes('error')) {
            toast.classList.add('error')
        }
        if (msg.includes('Invalid')) {
            toast.classList.add('invalid')
        }


        setTimeout(() => {
            toast.remove();
        }, 6000);
    }

    // Show the toast message if success exists in session
    <?php if(session('success')): ?>
    showToast(successMsg);
    <?php endif; ?>
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/client-details.blade.php ENDPATH**/ ?>