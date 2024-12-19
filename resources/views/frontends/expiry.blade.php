@extends('frontends.layouts.main')

@section('main-container')

<div class="expiry-page">
    <div class="expiry-filters">
        <!-- Any filters can go here -->
    </div>

    <div class="expiry-table">
    <table>
    <thead>
        <tr>
            <th>Domain Name</th>
            <th>Service Type</th>
            <th>Expiry Date</th>
            <th>Amount</th>
            <th>Days Left</th>
        </tr>
    </thead>
    <tbody>
        @foreach($servicesData as $service)
            <tr>
                <td>{{ $service['domain_name'] }}</td>
                <td>{{ $service['service_type'] }}</td>
                <td>{{ $service['expiry_date'] ?? 'N/A' }}</td>
                <td>{{ $service['amount'] ?? 'N/A' }}</td>
                <td>{{ $service['days_left'] }} days</td>
            </tr>
        @endforeach
    </tbody>
</table>

    </div>
</div>

@endsection
