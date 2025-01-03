@extends('frontends.layouts.main')

@section('main-container')

<main>
    <div class="client-page">
       
        <div class="client-page-heading">
            <div class="client-page-heading-h2">
            <div class="number-category">
            @if($lastSelectedFilter)
            <p>Total number of {{ $lastSelectedFilter }} Clients - {{ $clients->count() }}</p>
            @else
            <p> <strong> All Clients-</strong> ({{ $clients->count() }})</p>
            @endif
        </div>
            </div>
            <div class="create-filter-search-clients">
                <div class="create-clients">
                    <button class="btn-create">
                        <a href="{{ url('add-new-clients') }}">
                            <img src="{{url('public/frontend/images/add-new.png')}}" alt="">
                        </a>
                    </button>
                </div>
                <div class="filter-section">
                    <div class="filter-payments" onclick="toggleFilterList()">
                        <img src="public/frontend/images/new-bar.png" alt="" class="barfilter">
                        <div class="filter-count">
                            @if($filterCount > 0)
                            <p>{{ $filterCount }}</p>
                            @endif
                        </div>
                        Filter
                    </div>
                    <div class="filter-options" style="display: none;">
                        <form action="{{ route('clients.index') }}" method="GET">
                            <!-- Category Filter -->
                            <div class="filter-item">
                                <label for="category">Category:</label>
                                <select id="category" name="filter_category" class="filter-select" onchange="updateSubcategoryFilterOptions()">
                                    <option value="">Select Category</option>
                                    <option value="Website" {{ request('filter_category') == 'Website' ? 'selected' : '' }}>Website</option>
                                    <option value="Microsoft" {{ request('filter_category') == 'Microsoft' ? 'selected' : '' }}>Microsoft</option>
                                    <option value="Hosting" {{ request('filter_category') == 'Hosting' ? 'selected' : '' }}>Hosting</option>
                                    <option value="Other" {{ request('filter_category') == 'Other' ? 'selected' : '' }}>Other</option>
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
                <div class="search-payments">
                    <div class="search-icon">
                        <img src="public/frontend/images/search-light-color.png" alt="" class="searchi-icon">
                    </div>
                    <form action="{{ route('clients.index') }}" method="GET" id="search-form">
                        <div class="search-text-area">
                            <input type="text" name="search" placeholder="search..." value="{{ request('search') }}" oninput="this.form.submit()">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modern-payments-table">
    <table>
        <thead>
            <tr>
                <th>SN</th>
                <th id="company-name">
                    Company Name
                    <img 
                        id="sort-company-name" 
                        src="{{ url('public/frontend/images/sort.png') }}" 
                        alt="Sort" 
                        data-order="{{ request('sort_order') === 'asc' ? 'desc' : 'asc' }}" 
                        style="cursor: pointer;">
                </th>
                <th id="website">
    Website
    <img 
        id="sort-website" 
        src="{{ url('public/frontend/images/sort.png') }}" 
        alt="Sort" 
        data-order="{{ request('sort_by') === 'website' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}" 
        style="cursor: pointer;" 
        onclick="sortColumn('website', this)">
</th>

                <!-- <th>Rating</th> -->
            </tr>
        </thead>
        <tbody id="clients-tbody">
            @forelse ($clients as $index => $client)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="client-company-name">
                        <a href="{{ route('client.details', ['id' => $client->id]) }}">
                            {{ $client->company_name ?? '' }}
                        </a>
                    </td>

                    <td class="client-domain-name">
                        @if (!empty($client->website))
                            @php
                                $url = preg_match('/^(http|https):\/\//', $client->website) ? $client->website : 'http://' . $client->website;
                            @endphp
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $client->website }}</a>
                        @else
                            No Website
                        @endif
                    </td>
                    <!-- <td>{{ $client->rating ?? '' }}</td> -->
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    </div>

    <div id="add-clients-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Add Clients</h3>
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf
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
                    "Health & Wellness": [""],
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

        document.getElementById('sort-company-name').addEventListener('click', function () {
        const sortOrder = this.getAttribute('data-order'); // Get current sort order
        const url = `{{ route('clients.index') }}?sort_by=company_name&sort_order=${sortOrder}`; // Build URL for sorting
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Indicate this is an AJAX request
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Network response was not ok.");
            return response.text(); // Parse response as text (HTML)
        })
        .then(html => {
            document.getElementById('clients-tbody').innerHTML = html; // Update table body
            this.setAttribute('data-order', sortOrder === 'asc' ? 'desc' : 'asc'); // Toggle sort order for next click
        })
        .catch(error => {
            console.error('Error fetching sorted data:', error);
        });
    });


    function sortColumn(column, element) {
    const sortOrder = element.getAttribute('data-order'); // Get current sort order
    const url = new URL(window.location.href);
    url.searchParams.set('sort_by', column); // Set the column to sort by
    url.searchParams.set('sort_order', sortOrder); // Set the sorting order
    
    // Fetch updated data
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest', // Indicate this is an AJAX request
        }
    })
    .then(response => {
        if (!response.ok) throw new Error("Network response was not ok.");
        return response.text(); // Parse response as text (HTML)
    })
    .then(html => {
        document.getElementById('clients-tbody').innerHTML = html; // Update table body with sorted data
        // Toggle the sort order for the next click
        element.setAttribute('data-order', sortOrder === 'asc' ? 'desc' : 'asc');
    })
    .catch(error => {
        console.error('Error fetching sorted data:', error);
    });
}

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
    @if(session('success'))
    showToast(successMsg);
    @endif
    </script>
  
</main>
@endsection