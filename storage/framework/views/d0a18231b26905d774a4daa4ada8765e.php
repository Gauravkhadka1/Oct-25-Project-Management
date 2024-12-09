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
            <div class="client-page-heading-h2">
                <h2>All Clients</h2>
            </div>
            <div class="create-filter-search-clients">
                <div class="create-clients">
                    <button class="btn-create">
                        <a href="<?php echo e(url('add-new-clients')); ?>">
                            <img src="<?php echo e(url('public/frontend/images/add-new.png')); ?>" alt="">
                        </a>
                    </button>
                </div>
                <div class="filter-section">
                    <div class="filter-payments" onclick="toggleFilterList()">
                        <img src="public/frontend/images/bars-filter.png" alt="" class="barfilter">
                        <div class="filter-count">
                            <?php if($filterCount > 0): ?>
                            <p><?php echo e($filterCount); ?></p>
                            <?php endif; ?>
                        </div>
                        Filter
                    </div>
                    <div class="filter-options" style="display: none;">
                        <form action="<?php echo e(route('clients.index')); ?>" method="GET">
                            <!-- Category Filter -->
                            <div class="filter-item">
                                <label for="category">Category:</label>
                                <select id="category" name="filter_category" class="filter-select" onchange="updateSubcategoryFilterOptions()">
                                    <option value="">Select Category</option>
                                    <option value="Website" <?php echo e(request('filter_category') == 'Website' ? 'selected' : ''); ?>>Website</option>
                                    <option value="Microsoft" <?php echo e(request('filter_category') == 'Microsoft' ? 'selected' : ''); ?>>Microsoft</option>
                                    <option value="Hosting" <?php echo e(request('filter_category') == 'Hosting' ? 'selected' : ''); ?>>Hosting</option>
                                    <option value="Other" <?php echo e(request('filter_category') == 'Other' ? 'selected' : ''); ?>>Other</option>
                                </select>
                            </div>

                            <!-- Subcategory Filter -->
                            <div class="filter-item" id="subcategory-filter-container">
                                <label for="subcategory">Subcategory:</label>
                                <select id="subcategory-filter" name="filter_subcategory" class="filter-select" onchange="updateAdditionalSubcategoryFilterOptions()">
                                    <option value="">Select Subcategory</option>
                                    <!-- Subcategory options will be dynamically populated -->
                                </select>
                            </div>

                            <!-- Additional Subcategory Filter -->
                            <div class="filter-item" id="additional-subcategory-filter-container">
                                <label for="additional_subcategory">Additional Subcategory:</label>
                                <select id="additional-subcategory-filter" name="filter_additional_subcategory" class="filter-select">
                                    <option value="">Select Additional Subcategory</option>
                                    <!-- Additional subcategory options will be dynamically populated -->
                                </select>
                            </div>

                            <button type="submit">Apply Filter</button>
                        </form>
                    </div>
                </div>
                <div class="search-clients">
                    <div class="search-icon">
                        <img src="public/frontend/images/search-icon.png" alt="" class="searchi-icon">
                    </div>
                    <form action="<?php echo e(route('clients.index')); ?>" method="GET" id="search-form">
                        <div class="search-text-area">
                            <input type="text" name="search" placeholder="search clients..." value="<?php echo e(request('search')); ?>" oninput="this.form.submit()">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Number Category Section -->
        <div class="number-category">
            <?php if($lastSelectedFilter): ?>
            <p>Total number of <?php echo e($lastSelectedFilter); ?> Clients - <?php echo e($clients->count()); ?></p>
            <?php else: ?>
            <p> <strong> Total number of Clients -</strong> <?php echo e($clients->count()); ?></p>
            <?php endif; ?>
        </div>
        <div class="modern-payments-table">
            <table>
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Company Name</th>
                        <th>Website</th>
                        <th>Category</th>
                        <th>Address</th>
                        <th>Contact Person</th>
                        <th>C. Phone</th>
                        <th>C. Email</th>
                        <th>Rating</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($client->company_name ?? ''); ?></td>
                        <td>
                            <?php if(!empty($client->website)): ?>
                            <?php
                            $url = preg_match('/^(http|https):\/\//', $client->website) ? $client->website : 'http://' . $client->website;
                            ?>
                            <a href="<?php echo e($url); ?>" target="_blank" rel="noopener noreferrer"><?php echo e($client->website); ?></a>
                            <?php else: ?>
                            No Website
                            <?php endif; ?>
                        </td>


                        <td><?php echo e($client->category ?? ''); ?></td>
                        <td><?php echo e($client->address ?? ''); ?></td>
                        <td><?php echo e($client->contact_person ?? ''); ?></td>
                        <td><?php echo e($client->company_phone ?? ''); ?></td>
                        <td><?php echo e($client->company_email ?? ''); ?></td>
                        <td><?php echo e($client->rating ?? ''); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">No data available</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="add-clients-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Add Clients</h3>
            <form action="<?php echo e(route('clients.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="company-details">
                    <div class="form-group">
                        <label for="company_name">Company Name:</label>
                        <input type="text" id="company_name" name="company_name" nullable>
                    </div>


                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select id="category" name="category" nullable>
                            <option value="">Select Category</option>
                            <option value="Website">Website</option>
                            <option value="Microsoft">Microsoft</option>
                            <option value="Hosting">Hosting</option>
                        </select>
                    </div>

                    <!-- Subcategory Selection -->
                    <div class="form-group" id="subcategory-container" style="display: none;">
                        <label for="subcategory">Subcategory:</label>
                        <select id="subcategory" name="subcategory" nullable>
                            <option value="">Select Subcategory</option>
                        </select>
                    </div>

                    <!-- Additional Subcategory Selection -->
                    <div class="form-group" id="additional-subcategory-container" style="display: none;">
                        <label for="additional_subcategory">Additional Subcategory:</label>
                        <select id="additional_subcategory" name="additional_subcategory" nullable>
                            <option value="">Select Additional Subcategory</option>
                        </select>
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
                </div>

                <div class="contact-person-details">
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
                </div>

                <button type="submit" class="submit-btn">Add Client</button>
                <button type="button" class="btn-cancel" onclick="closeAddClientsModal()">Cancel</button>
        </div>
        </form>
    </div>
    </div>
    <script>
        function toggleFilterList() {
            const filterOptions = document.querySelector('.filter-options');
            filterOptions.style.display = filterOptions.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('click', function(event) {
            const filterDiv = document.querySelector('.filter-payments');
            const filterOptions = document.querySelector('.filter-options');
            if (!filterDiv.contains(event.target) && !filterOptions.contains(event.target)) {
                filterOptions.style.display = 'none';
            }
        });

        // JavaScript to automatically fade out the success message
        document.addEventListener('DOMContentLoaded', function() {
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

        function openAddClientsModal() {
            document.getElementById('add-clients-modal').style.display = 'block';
        }

        // close create payments
        function closeAddClientsModal() {
            document.getElementById('add-clients-modal').style.display = 'none';
        }

        // For adding multiple Categories 
        document.addEventListener('DOMContentLoaded', function() {
            const categoryData = {
                "Website": {
                    "Company": ["Manpower", "Hydropower", "Other"],
                    "NGO/ INGO": [],
                    "Tourism": [],
                    "Education": ["Edu Consultancy", "School", "College", "Other"],
                    "News": [""],
                    "eCommerce": ["Product Catlog", "ecommerce", "Other"],
                    "Hospitality": ["Hotel & Cafe", "Resort", "Other"],
                    "Personal Portfolio": [],
                    "other": [""]
                },
                "Microsoft": {
                    "Paid": [],
                    "Non Profit": ["Education", "NGO/ INGO"]
                },
                "Hosting": {

                },
                "Other": {

                }
            };
            const categoryFilterSelect = document.getElementById('category');
            const subcategoryFilterSelect = document.getElementById('subcategory-filter');
            const additionalSubcategoryFilterSelect = document.getElementById('additional-subcategory-filter');

            function updateSubcategoryFilterOptions() {
                const selectedCategory = categoryFilterSelect.value;
                subcategoryFilterSelect.innerHTML = '<option value="">Select Subcategory</option>';
                additionalSubcategoryFilterSelect.innerHTML = '<option value="">Select Additional Subcategory</option>';

                if (selectedCategory && categoryData[selectedCategory]) {
                    const subcategories = Object.keys(categoryData[selectedCategory]);
                    subcategories.forEach(function(subcategory) {
                        const option = document.createElement('option');
                        option.value = subcategory;
                        option.text = subcategory;
                        subcategoryFilterSelect.appendChild(option);
                    });
                }
            }

            function updateAdditionalSubcategoryFilterOptions() {
                const selectedCategory = categoryFilterSelect.value;
                const selectedSubcategory = subcategoryFilterSelect.value;
                additionalSubcategoryFilterSelect.innerHTML = '<option value="">Select Additional Subcategory</option>';

                if (selectedSubcategory && categoryData[selectedCategory][selectedSubcategory]) {
                    const additionalSubcategories = categoryData[selectedCategory][selectedSubcategory];
                    additionalSubcategories.forEach(function(additionalSubcategory) {
                        const option = document.createElement('option');
                        option.value = additionalSubcategory;
                        option.text = additionalSubcategory;
                        additionalSubcategoryFilterSelect.appendChild(option);
                    });
                }
            }

            categoryFilterSelect.addEventListener('change', updateSubcategoryFilterOptions);
            subcategoryFilterSelect.addEventListener('change', updateAdditionalSubcategoryFilterOptions);
        });
        const categorySelect = document.getElementById('category');
        const subcategorySelect = document.getElementById('subcategory');
        const additionalSubcategorySelect = document.getElementById('additional_subcategory');
        const subcategoryContainer = document.getElementById('subcategory-container');
        const additionalSubcategoryContainer = document.getElementById('additional-subcategory-container');

        categorySelect.addEventListener('change', function() {
            const selectedCategory = categorySelect.value;
            subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
            additionalSubcategorySelect.innerHTML = '<option value="">Select Additional Subcategory</option>';
            additionalSubcategoryContainer.style.display = 'none';

            if (selectedCategory) {
                subcategoryContainer.style.display = 'block';
                const subcategories = Object.keys(categoryData[selectedCategory]);
                subcategories.forEach(function(subcategory) {
                    const option = document.createElement('option');
                    option.value = subcategory;
                    option.text = subcategory;
                    subcategorySelect.appendChild(option);
                });
            } else {
                subcategoryContainer.style.display = 'none';
            }
        });

        subcategorySelect.addEventListener('change', function() {
            const selectedCategory = categorySelect.value;
            const selectedSubcategory = subcategorySelect.value;
            additionalSubcategorySelect.innerHTML = '<option value="">Select Additional Subcategory</option>';

            if (selectedSubcategory && categoryData[selectedCategory][selectedSubcategory]) {
                additionalSubcategoryContainer.style.display = 'block';
                const additionalSubcategories = categoryData[selectedCategory][selectedSubcategory];
                additionalSubcategories.forEach(function(additionalSubcategory) {
                    const option = document.createElement('option');
                    option.value = additionalSubcategory;
                    option.text = additionalSubcategory;
                    additionalSubcategorySelect.appendChild(option);
                });
            } else {
                additionalSubcategoryContainer.style.display = 'none';
            }
        });
    </script>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/clients.blade.php ENDPATH**/ ?>