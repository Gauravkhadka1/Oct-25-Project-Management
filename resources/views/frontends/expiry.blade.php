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
                    <!-- <th>SN</th> -->
                    <th>
                        Domain
                        <a href="{{ route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'website', 'days_filter' => request('days_filter')]) }}">
                            <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
                        </a>
                    </th>
                    <!-- <th>
                        Space
                        <a href="{{ route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'hosting_space', 'days_filter' => request('days_filter')]) }}">
                            <img src="{{ url('public/frontend/images/sort.png') }}" alt="Sort">
                        </a>
                    </th> -->
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
                    <th>E. Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($clients as $index => $client)
                    @php
                    $endDate = \Carbon\Carbon::parse($client->hosting_expiry_date);
                    $daysLeft = (int) now()->diffInDays($endDate, false); // Force integer
                    @endphp
                    <tr style="{{ $daysLeft <= 0 ? 'background-color: #F0434C; color: #f5f5f5;' : '' }}">
                        <!-- <td>{{ $index + 1 }}</td> -->
                        <td class="client-domain">
                        <a href="{{ route('client.details', ['id' => $client->id]) }}">{{ $client->website }}</a>
                            </td>
                        <!-- <td>{{ $client->hosting_space }}</td> -->
                        <td>{{ $daysLeft . ' days' }}</td>
                        <td>{{ $client->hosting_amount }}</td>
                        <td>{{ $client->hosting_expiry_date }}</td>
                        <td></td>
                        <td>{{ $daysLeft > 0 ? 'Active' : 'Expired' }}</td>
                        <td></td>
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
