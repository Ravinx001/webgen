<div class="d-inline-flex">
    <a href="{{ route('admin.alerts.show', $alert['id']) }}"
       class="btn btn-link btn-sm"
       data-bs-toggle="tooltip"
       title="View Alert">
        <i class="ph-eye"></i>
    </a>
    <a href="{{ route('admin.alerts.edit', $alert['id']) }}"
       class="btn btn-link btn-sm"
       data-bs-toggle="tooltip"
       title="Edit Alert">
        <i class="ph-pencil"></i>
    </a>
    <button type="button"
            class="btn btn-link btn-sm text-primary"
            data-bs-toggle="tooltip"
            title="Toggle Status"
            onclick="toggleAlertStatus({{ $alert['id'] }})">
        <i class="ph-toggle-{{ ($alert['status'] ?? 'active') === 'active' ? 'right' : 'left' }}"></i>
    </button>
    <button type="button"
            class="btn btn-link btn-sm text-danger"
            data-bs-toggle="tooltip"
            title="Delete Alert"
            onclick="deleteAlert({{ $alert['id'] }})">
        <i class="ph-trash"></i>
    </button>
</div>
