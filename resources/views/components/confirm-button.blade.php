@props([
    'action',
    'method' => 'DELETE',
    'title' => 'Are you sure?',
    'text' => 'This action cannot be undone.',
    'confirmText' => 'Yes, proceed',
    'label' => 'Delete',
])

<form method="POST" action="{{ $action }}" class="d-inline confirm-form">
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif
    <button
        type="button"
        {{ $attributes->merge(['class' => 'btn btn-sm btn-outline-danger']) }}
        onclick="Inkora.confirmSubmit(this.closest('form'), {
            title: @js($title),
            text: @js($text),
            confirmButtonText: @js($confirmText)
        })"
    >
        {{ $label }}
    </button>
</form>
