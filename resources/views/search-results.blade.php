@extends('frontends.layouts.main')

@section('main-container')
   <main>
   <h1>Search Results</h1>

        @if($results->isEmpty())
            <p>No results found.</p>
        @else
            <ul>
                @foreach($results as $result)
                    <li>{{ $result->heading }}</li> <!-- Adjust for the actual fields -->
                @endforeach
            </ul>
        @endif
   </main>
@endsection