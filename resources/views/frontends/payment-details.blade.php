@extends('frontends.layouts.main')

@section('main-container')

<div class="payment-detail-page">
    @foreach ($payments as $payment)
    <div class="payment-detail">
        @foreach ($payments as $payment)
        <div class="comapny-name">
            <p>{{ $payment->company_name }}</p>
        </div>
        @endforeach
        <div class="add-payment-task">
            <button>
            <h2>Add task</h2>
            </button>
            
        </div>
    </div>
    @endforeach
    <div class="payment-comments">
        <h2>Comments</h2>
        <div id="view-activities-modal" class="modal">
    <div class="modal-content">
        <h3>Activities</h3>
        <div id="activities-list">
            <!-- Activities will be populated here -->
        </div>

        <!-- Sticky Add Activity Section -->
        <div id="add-activity-section" class="sticky-section">
            <form id="add-activity-form" action="{{ route('payments-activities.store') }}" method="POST">
                @csrf
                <input type="hidden" name="payments_id" id="activity-payments-id">
                <input type="text" name="details" id="activity-details" placeholder="Add Comments..." required>
                <div id="suggestions"></div>
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">Add<div id="loading-spinner" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
                    <img src="{{url('frontend/images/spinner.gif')}}" alt="Loading...">
                </div>
                </button>
                </div>
            </form>
        </div>

        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeViewActivitiesModal()">Close</button>
        </div>
    </div>
    </div>
</div>
@endsection