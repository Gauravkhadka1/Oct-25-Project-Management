@forelse ($clients as $index => $client)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $client->company_name ?? '' }}</td>
        <td>
            @if (!empty($client->website))
                @php
                    $url = preg_match('/^(http|https):\/\//', $client->website) ? $client->website : 'http://' . $client->website;
                @endphp
                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $client->website }}</a>
            @else
                No Website
            @endif
        </td>
        <td>{{ $client->rating ?? '' }}</td>
    </tr>
@empty
    <tr>
        <td colspan="4" style="text-align: center;">No data available</td>
    </tr>
@endforelse