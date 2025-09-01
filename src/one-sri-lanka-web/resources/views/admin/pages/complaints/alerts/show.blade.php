@extends('admin.layouts.master')

@section('content')
    @component('admin.components.breadcrumb')
        @slot('title') {{ $title }} @endslot
        @slot('subtitle') {{ $subtitle }} @endslot
    @endcomponent

    <!-- Content area -->
    <div class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Alert Details</h5>
                <div>
                    <a href="{{ route('admin.alerts.edit', $alert['id']) }}" class="btn btn-primary btn-sm">
                        <i class="ph-pencil"></i> Edit Alert
                    </a>
                    <a href="{{ route('admin.alerts.index') }}" class="btn btn-light btn-sm">
                        <i class="ph-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Alert Information -->
                    <div class="col-lg-8">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">Alert Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <span class="fw-semibold">Title:</span>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $alert['title'] }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <span class="fw-semibold">Description:</span>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $alert['description'] }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <span class="fw-semibold">Category:</span>
                                    </div>
                                    <div class="col-sm-9">
                                        <span class="badge bg-{{ $alert['category'] === 'emergency' ? 'danger' : ($alert['category'] === 'security' ? 'warning' : 'info') }}">
                                            {{ ucfirst($alert['category']) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <span class="fw-semibold">Priority:</span>
                                    </div>
                                    <div class="col-sm-9">
                                        <span class="badge bg-{{ $alert['priority'] === 'critical' ? 'danger' : ($alert['priority'] === 'high' ? 'warning' : ($alert['priority'] === 'medium' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst($alert['priority']) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <span class="fw-semibold">Status:</span>
                                    </div>
                                    <div class="col-sm-9">
                                        <span class="badge bg-{{ $alert['status'] === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($alert['status']) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Metadata</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Alert ID</small>
                                    <span class="fw-semibold">#{{ $alert['id'] }}</span>
                                </div>

                                @if(isset($alert['created_at']))
                                <div class="mb-3">
                                    <small class="text-muted d-block">Created</small>
                                    <span class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($alert['created_at'])->format('M d, Y H:i') }}
                                    </span>
                                </div>
                                @endif

                                @if(isset($alert['updated_at']))
                                <div class="mb-3">
                                    <small class="text-muted d-block">Last Updated</small>
                                    <span class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($alert['updated_at'])->format('M d, Y H:i') }}
                                    </span>
                                </div>
                                @endif

                                @if(isset($alert['created_by']))
                                <div class="mb-3">
                                    <small class="text-muted d-block">Created By</small>
                                    <span class="fw-semibold">{{ $alert['created_by'] }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm"
                                            onclick="toggleAlertStatus({{ $alert['id'] }})">
                                        <i class="ph-toggle-{{ $alert['status'] === 'active' ? 'right' : 'left' }}"></i>
                                        {{ $alert['status'] === 'active' ? 'Deactivate' : 'Activate' }} Alert
                                    </button>

                                    <a href="{{ route('admin.alerts.edit', $alert['id']) }}"
                                       class="btn btn-outline-success btn-sm">
                                        <i class="ph-pencil"></i> Edit Alert
                                    </a>

                                    <button type="button"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="deleteAlert({{ $alert['id'] }})">
                                        <i class="ph-trash"></i> Delete Alert
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->

@endsection

@section('scripts')
    <script>
        function toggleAlertStatus(alertId) {
            if (confirm('Are you sure you want to toggle the status of this alert?')) {
                fetch(`{{ route('admin.alerts.toggle-status', ':id') }}`.replace(':id', alertId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error toggling alert status: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error toggling alert status');
                });
            }
        }

        function deleteAlert(alertId) {
            if (confirm('Are you sure you want to delete this alert? This action cannot be undone.')) {
                fetch(`{{ route('admin.alerts.destroy', ':id') }}`.replace(':id', alertId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route('admin.alerts.index') }}';
                    } else {
                        alert('Error deleting alert: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting alert');
                });
            }
        }
    </script>
@endsection
