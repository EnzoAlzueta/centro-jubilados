@props(['status'])

@if ($status)
<div {{ $attributes->merge(['class' => 'position-fixed top-0 start-50 translate-middle mt-5']) }} style="z-index: 9999;
    min-width: 300px;">
    <div class="alert alert-primary alert-dismissible fade show shadow-lg" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div class="small fw-medium">
                {{ $status }}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif