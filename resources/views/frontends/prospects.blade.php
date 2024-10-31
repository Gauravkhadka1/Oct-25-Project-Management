@extends('frontends.layouts.main')

@section('main-container')

<main>
    @if (session('success'))
    <div id="success-message" style="position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
        {{ session('success') }}
    </div>

    @endif

    <div class="prospects-list">
        <div class="prospect-heading">
            <div class="prospect-heading">
                <h2>Prospects</h2>
            </div>
            <div class="create-prospect">
                <button class="btn-create" onclick="openCreateProspectModal()">Create Prospect</button>
            </div>
        </div>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th >
                        Name
                        <a href="#" onclick="toggleFilter('name-filter')">
                            <!-- <i class="fas fa-filter"></i>  -->
                            <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                        </a>
                        <div id="name-filter" class="filter-dropdown" style="display: none;">
                            <form action="{{ route('prospects.index') }}" method="GET">
                                <select name="sort_name" onchange="this.form.submit()">
                                    <option value="">Select</option>
                                    <option value="asc" {{ request('sort_name') == 'asc' ? 'selected' : '' }}>A-Z</option>
                                    <option value="desc" {{ request('sort_name') == 'desc' ? 'selected' : '' }}>Z-A</option>
                                </select>
                            </form>
                        </div>
                    </th>
                    <th>
                        Category
                        <a href="#" onclick="toggleFilter('category-filter')">
                            <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                        </a>
                        <div id="category-filter" class="filter-dropdown" style="display: none;">
                            <form action="{{ route('prospects.index') }}" method="GET">
                                <select name="filter_category" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    <option value="Ecommerce" {{ request('filter_category') == 'Ecommerce' ? 'selected' : '' }}>Ecommerce</option>
                                    <option value="NGO/ INGO" {{ request('filter_category') == 'NGO/ INGO' ? 'selected' : '' }}>NGO/ INGO</option>
                                    <option value="Tourism" {{ request('filter_category') == 'Tourism' ? 'selected' : '' }}>Tourism</option>
                                    <option value="Education" {{ request('filter_category') == 'Education' ? 'selected' : '' }}>Education</option>
                                    <option value="Microsoft" {{ request('filter_category') == 'Microsoft' ? 'selected' : '' }}>Microsoft</option>
                                    <option value="Other" {{ request('filter_category') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </form>
                        </div>
                    </th>
                    <th>
                        Inquiry Date
                        <a href="#" onclick="toggleFilter('inquirydate-filter')">
                            <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                        </a>
                        <div id="inquirydate-filter" class="filter-dropdown" style="display: none;">
                            <form action="{{ route('prospects.index') }}" method="GET">
                                <select name="sort_inquirydate" id="sort_inquirydate" onchange="handleFilterChange(this)">
                                    <option value="">Select</option>
                                    <option value="desc" {{ request('sort_inquirydate') == 'desc' ? 'selected' : '' }}>Most Recent</option>
                                    <option value="asc" {{ request('sort_inquirydate') == 'asc' ? 'selected' : '' }}>Oldest</option>
                                    <option value="range">Choose Date Range</option>
                                </select>

                                <!-- Date range input fields (initially hidden) -->
                                <div id="date-range-fields" style="display: none;">
                                    <label for="from_date">From:</label>
                                    <input type="date" name="from_date" value="{{ request('from_date') }}">

                                    <label for="to_date">To:</label>
                                    <input type="date" name="to_date" value="{{ request('to_date') }}">
                                </div>

                                <button type="submit" id="date-range-submit" style="display: none;">Filter</button>
                            </form>
                        </div>
                    </th>



                    <th>
                        Probability
                        <a href="#" onclick="toggleFilter('probability-filter')">
                            <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                        </a>
                        <div id="probability-filter" class="filter-dropdown" style="display: none;">
                            <form action="{{ route('prospects.index') }}" method="GET">
                                <select name="sort_probability" onchange="this.form.submit()">
                                    <option value="">Select</option>
                                    <option value="desc" {{ request('sort_probability') == 'desc' ? 'selected' : '' }}>Higher to Lower</option>
                                    <option value="asc" {{ request('sort_probability') == 'asc' ? 'selected' : '' }}>Lower to Higher</option>
                                </select>
                            </form>
                        </div>
                    </th>
                    <th>Activities</th>
                    <th>
                        Status
                        <a href="#" onclick="toggleFilter('status-filter')">
                            <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                        </a>
                        <div id="status-filter" class="filter-dropdown" style="display: none;">
                            <form action="{{ route('prospects.index') }}" method="GET">
                                <select name="sort_status" onchange="this.form.submit()">
                                    <option value="">All Statuses</option>
                                    <option value="Not Responded" {{ request('sort_status') == 'Not Responded' ? 'selected' : '' }}>Not Responded</option>
                                    <option value="Dealing" {{ request('sort_status') == 'Dealing' ? 'selected' : '' }}>Dealing</option>
                                    <option value="Converted" {{ request('sort_status') == 'Converted' ? 'selected' : '' }}>Converted</option>
                                    <option value="Missed" {{ request('sort_status') == 'Missed' ? 'selected' : '' }}>Missed</option>
                                </select>
                            </form>
                        </div>
                    </th>
                    <th>Edit</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($prospects as $key => $prospect)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <span class="prospect-name">{{ $prospect->company_name }}</span>
                        <button class="btn-see-details" data-contact_person="{{ $prospect->contact_person }}" data-phone="{{ $prospect->phone_number }}" data-email="{{ $prospect->email }}" data-message="{{ $prospect->message }}">
                            See Details
                        </button>
                    </td>


                    <td>{{ $prospect->category }}</td>

                    <td>{{ $prospect->inquirydate ? $prospect->inquirydate->format('Y-m-d h:i A') : 'N/A' }}</td>
                    <td>{{ $prospect->probability }}%</td>
                    <td>
                        <button class="btn-add-activity" onclick="openAddActivityModal({{ $prospect->id }})">Add Activity</button>
                        <button class="btn-view-activities" onclick="viewActivities({{ $prospect->id }})">View Activities</button>
                    </td>


                    <td>{{ $prospect->status }}</td>
                    <td>
                        <button class="btn-create" onclick="openEditProspectModal({{ json_encode($prospect) }})">Edit</button>
                        <form action="{{ route('prospects.destroy', $prospect->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this prospect?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-cancel">Delete</button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

    </div>


    <!-- details modals -->
    <!-- See Details Modal -->
    <div id="details-modal" class="details-modal" style="display: none;">
        <div class="details-modal-content">
            <h3>Prospect Details</h3>
            <p><strong>Contact person:</strong> <span id="modal-contact_person"></span></p>
            <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
            <p><strong>Email:</strong> <span id="modal-email"></span></p>
            <p><strong>Message:</strong> <span id="modal-message"></span></p>
            <div class="details-modal-buttons">
                <button type="button" class="btn-cancel" onclick="closeDetailsModal()">Close</button>
            </div>
        </div>
    </div>



    <!-- see more message modal -->
    <div id="message-modal" class="messagemodal" style="display: none;">
        <div class="messagemodal-content">
            <h3>Full Message</h3>
            <p id="full-message-content"></p> <!-- This will display the full message -->
            <div class="messagemodal-buttons">
                <button type="button" class="btn-cancel" onclick="closeMessageModal()">Close</button>
            </div>
        </div>
    </div>


    <!-- Create Prospect Modal -->
    <div id="create-prospect-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Create New Prospect</h3>
            <form action="{{ route('prospects.store') }}" method="POST">
                @csrf
                <label for="prospect-company_name">Prospect Name:</label>
                <input type="text" name="company_name" id="prospect-company_name" required><br>

                <label for="category">Category</label>
                <select name="category" id="category">
                    <option value="Ecommerce">Ecommerce</option>
                    <option value="NGO/ INGO">NGO/ INGO</option>
                    <option value="Tourism">Tourism</option>
                    <option value="Education">Education</option>
                    <option value="Microsoft">Microsoft</option>
                    <option value="Other">Other</option>
                </select><br>

                <label for="prospect-contact_person">Contact Person:</label>
                <input type="text" name="contact_person" id="prospect-contact_person"><br>

                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number"><br>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email"><br>

                <label for="message">Message:</label>
                <textarea name="message" id="message"></textarea><br>

                <label for="probability">Probability</label>
                <input type="number" name="probability" id="probability"><br>

                <label for="inquirydate">Inquiry Date</label>
                <input type="datetime-local" name="inquirydate" id="inquirydate"><br>

                <label for="activities">Activities</label>
                <input type="text" name="activities" id="activities"><br>

                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="Not Responded">Not Responded</option>
                    <option value="Dealing">Dealing</option>
                    <option value="Converted">Converted</option>
                    <option value="Missed">Missed</option>
                </select><br>

                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Add Prospect</button>
                    <button type="button" class="btn-cancel" onclick="closeCreateProspectModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Prospect Modal -->
    <div id="edit-prospect-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Edit Prospect</h3>
            <form id="edit-prospect-form" action="{{ route('prospects.update', '') }}" method="POST">
                @csrf
                @method('PUT')
                <label for="edit-prospect-company_name">Company Name:</label>
                <input type="text" name="company_name" id="edit-company_name"><br>

                <label for="edit-category">Category</label>
                <select name="category" id="edit-category">
                    <option value="Ecommerce">Ecommerce</option>
                    <option value="NGO/ INGO">NGO/ INGO</option>
                    <option value="Tourism">Tourism</option>
                    <option value="Education">Education</option>
                    <option value="Microsoft">Microsoft</option>
                    <option value="Other">Other</option>
                </select><br>

                <label for="edit-contact_person">Contact Person:</label>
                <input type="text" name="contact_person" id="edit-contact_person"><br>

                <label for="edit-phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="edit-phone_number"><br>

                <label for="edit-email">Email:</label>
                <input type="email" name="email" id="edit-email"><br>

                <label for="edit-message">Message:</label>
                <textarea name="message" id="edit-message"></textarea><br>

                <label for="edit-probability">Probability</label>
                <input type="number" name="probability" id="edit-probability"><br>

                <label for="edit-inquirydate">Inquiry Date</label>
                <input type="datetime-local" name="inquirydate" id="edit-inquirydate"><br>

                <label for="edit-activities">Activities</label>
                <input type="text" name="activities" id="edit-activities"><br>

                <label for="edit-status">Status</label>
                <select name="status" id="edit-status">
                    <option value="Not Responded">Not Responded</option>
                    <option value="Dealing">Dealing</option>
                    <option value="Converted">Converted</option>
                    <option value="Missed">Missed</option>
                </select><br>

                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Update Prospect</button>
                    <button type="button" class="btn-cancel" onclick="closeEditProspectModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Add Activity Modal -->
    <div id="add-activity-modal" class="modal">
        <div class="modal-content">
            <h3>Add Activity</h3>
            <form id="add-activity-form" action="{{ route('activities.store') }}" method="POST">
                @csrf
                <input type="hidden" name="prospect_id" id="activity-prospect-id">

                <label for="activity-details">Activity Details:</label>
                <input type="text" name="details" id="activity-details" required>
                <div id="mention-suggestions" style="display: none;"></div> <!-- For At.js suggestions -->

                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Add Activity</button>
                    <button type="button" class="btn-cancel" onclick="closeAddActivityModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <!-- View Activities Modal -->
    <div id="view-activities-modal" class="modal">
        <div class="modal-content">
            <h3>Activities</h3>
            <div id="activities-list">
                <!-- Activities will be populated here -->
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn-cancel" onclick="closeViewActivitiesModal()">Close</button>
            </div>
        </div>
    </div>


</main>

@endsection
