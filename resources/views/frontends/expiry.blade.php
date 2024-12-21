@extends('frontends.layouts.main')

@section('main-container')

<div class="expiry-page">
    <div class="expiry-top">
        <div class="expiry-category">
            <p>All </p>
        </div>
        <div class="expiry-filter-search">
            <div class="filter-payments" onclick="toggleFilterList()">
                <img src="public/frontend/images/new-bar.png" alt="" class="barfilter">
                <div class="filter-count">

                    <p></p>

                </div>
                Filter
            </div>
            <div class="search-payments">
                <div class="search-icon">
                    <img src="public/frontend/images/search-light-color.png" alt="" class="searchi-icon">
                </div>
                <form action="{{ route('expiry.index') }}" method="GET" id="search-form">
                    <div class="search-text-area">
                        <input type="text" name="search" placeholder="search..." value="{{ request('search') }}" oninput="this.form.submit()">
                    </div>
                </form>
            </div>
        </div>
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
                <tr class="
                        {{ $service['days_left'] <= -30 ? 'expired-critical' : '' }}
                        {{ $service['days_left'] >= 0 && $service['days_left'] <= 7 ? 'expiring-soon' : '' }}
                        {{ $service['days_left'] >= -29 && $service['days_left'] <= 0 ? 'expiring-immediate' : '' }}
                    ">

                    <td class="client-domain">
                        <a href="{{ route('client.details', ['id' => $service['client_id']]) }}">
                            {{ $service['domain_name'] }}
                        </a>
                    </td>
                    <td>{{ $service['service_type'] }}</td>
                    <td>{{ $service['days_left'] }} days</td>
                    <td>
                        @if($service['amount'])
                        {{ number_format($service['amount']) }}
                        @else
                        N/A
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($service['expiry_date'])->format('d-m-Y') ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>


@endsection