@extends('frontends.layouts.main')

@section('main-container')

<div class="payment-detail-page">
    <div class="payment-detail">
    @foreach ($payments as $payment)
        <div class="comapny-name">
            <p>{{ $payment->company_name }}</p>
        </div>
        @endforeach
    </div>
    <div class="payment-comments">

    </div>
</div>
@endsection