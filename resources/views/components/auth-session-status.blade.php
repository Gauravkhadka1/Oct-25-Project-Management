@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'custom-alert custom-success-message']) }}>
        {{ $status }}
    </div>
@endif

