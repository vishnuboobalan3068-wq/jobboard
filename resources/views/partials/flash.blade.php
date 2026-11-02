@foreach(['success' => 'check-circle', 'error' => 'exclamation-triangle'] as $type => $icon)
    @if(session($type))
        <div class="alert alert-{{ $type === 'error' ? 'danger' : 'success' }} alert-dismissible fade show" role="alert">
            <i class="bi bi-{{ $icon }} me-2"></i>{{ session($type) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endforeach

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Please fix the following:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
