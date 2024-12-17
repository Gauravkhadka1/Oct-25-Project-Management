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
                    <th>SN</th>
                    <th>
                        Domain
                        <a href="{{ route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'website', 'days_filter' => request('days_filter')]) }}">
                            <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
                        </a>
                    </th>
                    <th>
                        Space
                        <a href="{{ route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'hosting_space', 'days_filter' => request('days_filter')]) }}">
                            <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
                        </a>
                    </th>
                    <th>
                        Days Left
                        <a href="{{ route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'days_left', 'days_filter' => request('days_filter')]) }}">
                            <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
                        </a>
                    </th>
                    <th>
                        Amount
                        <a href="{{ route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'hosting_amount', 'days_filter' => request('days_filter')]) }}">
                            <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
                        </a>
                    </th>
                    <th>
                        End Date
                        <a href="{{ route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'hosting_expiry_date', 'days_filter' => request('days_filter')]) }}">
                            <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
                        </a>
                    </th>
                    <th>Service Type</th>
                    <th>
                        Status
                        <a href="{{ route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'status', 'days_filter' => request('days_filter')]) }}">
                            <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
                        </a>
                    </th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($clients as $index => $client)
                    <tr style="{{ $client->days_left <= 0 ? 'background-color: #ffcccc;' : '' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $client->website }}</td>
                        <td>{{ $client->hosting_space }}</td>
                        <td>{{ $client->days_left }} days</td>
                        <td>{{ $client->amount }}</td>
                        <td>{{ $client->expiry_date }}</td>
                        <td>{{ $client->service_type }}</td> <!-- Service type (Domain or Hosting) -->
                        <td>{{ $client->days_left > 0 ? 'Active' : 'Expired' }}</td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center;">No clients found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
