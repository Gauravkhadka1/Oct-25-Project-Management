@extends('frontends.layouts.main')

@section('main-container')

<div class="expiry-page">
    <div class="expiry-filters">
        <!-- Any filters can go here -->
    </div>

    <div class="expiry-table">
        <table>
        <thead>
    <tr class="client-domain">
        <th>
            Domain Name
            <a href="{{ route('expiry.index', array_merge(request()->except('sort_by', 'sort_order'), ['sort_by' => 'domain_name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
            </a>
        </th>
        <th>
            Service Type
            <a href="{{ route('expiry.index', array_merge(request()->except('sort_by', 'sort_order'), ['sort_by' => 'service_type', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
            </a>
        </th>
        <th>
            Days Left
            <a href="{{ route('expiry.index', array_merge(request()->except('sort_by', 'sort_order'), ['sort_by' => 'days_left', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
            </a>
        </th>
        <th>
            Amount
            <a href="{{ route('expiry.index', array_merge(request()->except('sort_by', 'sort_order'), ['sort_by' => 'amount', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
            </a>
        </th>
        <th>
            Expiry Date
            <a href="{{ route('expiry.index', array_merge(request()->except('sort_by', 'sort_order'), ['sort_by' => 'expiry_date', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
            </a>
        </th>
    </tr>
</thead>


            <tbody>
                @foreach($servicesData as $service)
                    <tr>
                        <td>
                            <a href="{{ route('client.details', ['id' => $service['client_id']]) }}">
                                {{ $service['domain_name'] }}
                            </a>
                        </td>
                        <td>{{ $service['service_type'] }}</td>
                        <td>{{ $service['days_left'] }} days</td>
                        <td>{{ $service['amount'] ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($service['expiry_date'])->format('d-m-Y') ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<style>
.expiry-page {
    padding: 20px;
}
    .expiry-table img {
    width: 15px;
}
.expiry-table th {
    background-color:rgb(235, 238, 245);
    color: #2A2E34;
}
.client-domain a {
    text-decoration: none;
    color: #2A2E34;
    font-weight: 500;
}
.client-domain a:hover {
    text-decoration: underline;
}
</style>

@endsection
