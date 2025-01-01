@extends('frontends.layouts.main')

@section('main-container')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="expiry-page">
    <div class="expiry-top">
        <div class="expiry-category">
            <p>
                @php
                $filterLabels = [
                '35-31' => '35 Days',
                '30-16' => '30 Days',
                '15-8' => '15 Days',
                '7-1' => '7 Days',
                'today' => 'Expiring Today',
                'expired' => 'Expired',
                'All' => 'All',
                ];

                $filter = request('days_filter', 'All');
                $displayFilter = $filterLabels[$filter] ?? ucfirst($filter);
                @endphp
                {{ $displayFilter }}
            </p>
        </div>

        <div class="expiry-filter-search">
            <div class="filter-section">
                <div class="filter-payments" onclick="toggleFilterList()">
                    <img src="{{ url('public/frontend/images/new-bar.png') }}" alt="" class="barfilter">
                    <div class="filter-count">
                    </div>
                    Filter
                </div>
                <div class="filter-options" style="display: none;">
                    <form action="{{ route('expiry.index') }}" method="GET" id="days-filter-form">
                        <!-- Category Filter -->
                        <div class="filter-item">
    <label for="categories">Categories:</label>
    <select id="categories" name="filter_categories[]" class="filter-select" multiple>
        <option value="all" {{ in_array('all', request('filter_categories', [])) ? 'selected' : '' }}>All</option>
        <option value="website" {{ in_array('website', request('filter_categories', [])) ? 'selected' : '' }}>Website</option>
        <option value="domain" {{ in_array('domain', request('filter_categories', [])) ? 'selected' : '' }}>Domain</option>
        <option value="hosting" {{ in_array('hosting', request('filter_categories', [])) ? 'selected' : '' }}>Hosting</option>
        <option value="microsoft" {{ in_array('microsoft', request('filter_categories', [])) ? 'selected' : '' }}>Microsoft</option>
        <option value="maintenance" {{ in_array('maintenance', request('filter_categories', [])) ? 'selected' : '' }}>Maintenance</option>
        <option value="seo" {{ in_array('seo', request('filter_categories', [])) ? 'selected' : '' }}>SEO</option>
    </select>
</div>

                        <div class="filter-item">
                            <label for="due_date">Days Remaining:</label>
                            <div class="search-text-area">
                                <input
                                    type="number"
                                    name="days_filter"
                                    placeholder="Enter days..."
                                    value="{{ request('days_filter') }}"
                                    onchange="document.getElementById('days-filter-form').submit()">
                            </div>
                        </div>
                        <button type="submit">Apply Filter</button>
                    </form>
                </div>

            </div>
            <div class="search-payments">
                <div class="search-icon">
                    <img src="public/frontend/images/search-light-color.png" alt="" class="searchi-icon">
                </div>
                <form action="{{ route('expiry.index') }}" method="GET" id="search-form">
                    <div class="search-text-area">
                        <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" oninput="this.form.submit()">
                    </div>
                </form>
            </div>
        </div>

    </div>

    <div class="expiry-table">
        <table>
            <thead>
                <tr class="client-domain">
                    <th>Domain Name</th>
                    <th>Service Type</th>
                    <th>Days Left</th>
                    <th>Amount</th>
                    <!-- <th>Expiry Date</th> -->
                    <th>Email Sent On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                $totalAmount = 0;
                @endphp

                @foreach($servicesData as $service)
                @php
                $amount = $service['amount'] ?? 0;
                $totalAmount += $amount;
                @endphp

                <tr class=" 
                    {{ $service['days_left'] <= -30 ? 'expired-critical' : '' }}
                    {{ $service['days_left'] >= 0 && $service['days_left'] <= 7 ? 'expiring-soon' : '' }}
                    {{ $service['days_left'] >= -29 && $service['days_left'] <= 0 ? 'expiring-immediate' : '' }}">

                    <td class="client-domain">
                        <a href="{{ route('client.details', ['id' => $service['client_id']]) }}">
                            {{ $service['domain_name'] }}
                        </a>
                    </td>
                    <td>{{ $service['service_type'] }}</td>
                    <td>{{ $service['days_left'] }} days</td>
                    <td>
                        @if($amount)
                        {{ number_format($amount) }}
                        @else
                        N/A
                        @endif
                    </td>
                    <!-- <td>{{ \Carbon\Carbon::parse($service['expiry_date'])->format('d-m-Y') ?? 'N/A' }}</td> -->
                    <td>Date</td>
                    <td>
                       send
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Total Amount</strong></td>
                    <td colspan="2"><strong>{{ number_format($totalAmount) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<style>
    .action-select {
        width: 100px;
    }

    .days-input-area {
        display: none;
    }
</style>

<script>
    function toggleFilterList() {
        const filterOptions = document.querySelector('.filter-options');
        filterOptions.style.display = filterOptions.style.display === 'none' ? 'block' : 'none';

        // Populate the select options with the current selected values only if the options are shown
        if (filterOptions.style.display === 'block') {
            populateSelectedFilters();
        }
    }

    function populateSelectedFilters() {
        const categorySelect = document.getElementById('category');
        const amountSelect = document.getElementById('amount');

        const urlParams = new URLSearchParams(window.location.search);
        categorySelect.value = urlParams.get('filter_category') || '';
        amountSelect.value = urlParams.get('amount') || '';
    }

    // Optional: Close the filter options if clicking outside of them
    document.addEventListener('click', function(event) {
        const filterDiv = document.querySelector('.filter-payments');
        const filterOptions = document.querySelector('.filter-options');
        if (!filterDiv.contains(event.target) && !filterOptions.contains(event.target)) {
            filterOptions.style.display = 'none';
        }
    });

    function applyFilter() {
        const category = document.getElementById('category').value;
        const amount = document.getElementById('amount').value;

        const url = new URL(window.location.href);
        url.searchParams.set('filter_category', category);
        url.searchParams.set('amount', amount);


        window.location.href = url.toString();
    }

    document.addEventListener("DOMContentLoaded", function() {
    const selectedCategories = @json(request()->input('filter_categories', []));
    const categorySelect = document.getElementById('categories');
    selectedCategories.forEach(category => {
        const option = categorySelect.querySelector(`option[value="${category}"]`);
        if (option) option.selected = true;
    });
});

document.getElementById('categories').addEventListener('change', function () {
    if (this.value === 'all') {
        // Disable other selections when 'all' is selected
        [...this.options].forEach(option => {
            if (option.value !== 'all') {
                option.selected = false;
            }
        });
    }
});

document.querySelectorAll('.send-email-btn').forEach(button => {
    button.addEventListener('click', function () {
        const clientEmail = this.getAttribute('data-client-email');
        const serviceType = this.getAttribute('data-service-type');
        const expiryDate = this.getAttribute('data-expiry-date');
        const daysLeft = this.getAttribute('data-days-left');
        const domainName = this.getAttribute('data-domain-name');

        fetch('{{ route("send.expiry.email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                client_email: clientEmail,
                service_type: serviceType,
                expiry_date: expiryDate,
                days_left: daysLeft,
                domain_name: domainName
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Email sent successfully!');
                // Optionally, update the email-sent-on column in the UI
                this.closest('tr').querySelector('td:nth-child(5)').textContent = data.email_sent_on;
            } else {
                alert('Failed to send email: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while sending the email.');
        });
    });
});


</script>

@endsection