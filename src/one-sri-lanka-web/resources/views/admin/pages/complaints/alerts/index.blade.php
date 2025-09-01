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
                <h5 class="mb-0">Alerts Management</h5>
                <a href="{{ route('admin.alerts.create') }}" class="btn btn-primary">
                    <i class="ph-plus"></i> Add New Alert
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datatable-column-search-inputs">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($alerts) && $alerts->count() > 0)
                                @foreach($alerts as $alert)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">#{{ $alert['id'] }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">{{ $alert['title'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $alert['category'] === 'emergency' ? 'danger' : ($alert['category'] === 'security' ? 'warning' : 'info') }}">
                                                {{ ucfirst($alert['category']) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $alert['priority'] === 'critical' ? 'danger' : ($alert['priority'] === 'high' ? 'warning' : ($alert['priority'] === 'medium' ? 'info' : 'secondary')) }}">
                                                {{ ucfirst($alert['priority']) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted d-block" style="max-width: 200px;">
                                                {{ \Illuminate\Support\Str::limit($alert['description'], 50) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $alert['status'] === 'active' ? 'success' : 'secondary' }}">
                                                <i class="ph-{{ $alert['status'] === 'active' ? 'check-circle' : 'x-circle' }} me-1"></i>
                                                {{ ucfirst($alert['status']) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                {{ isset($alert['created_at']) ? date('M d, Y', strtotime($alert['created_at'])) : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <a href="{{ route('admin.alerts.show', $alert['id']) }}"
                                                   class="btn btn-light btn-sm"
                                                   data-bs-toggle="tooltip"
                                                   title="View Alert">
                                                    <i class="ph-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.alerts.edit', $alert['id']) }}"
                                                   class="btn btn-light btn-sm"
                                                   data-bs-toggle="tooltip"
                                                   title="Edit Alert">
                                                    <i class="ph-pencil"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-light btn-sm"
                                                        data-bs-toggle="tooltip"
                                                        title="Toggle Status"
                                                        onclick="toggleAlertStatus({{ $alert['id'] }})">
                                                    <i class="ph-toggle-{{ $alert['status'] === 'active' ? 'right' : 'left' }} text-primary"></i>
                                                </button>
                                                <button type="button"
                                                        class="btn btn-light btn-sm"
                                                        data-bs-toggle="tooltip"
                                                        title="Delete Alert"
                                                        onclick="deleteAlert({{ $alert['id'] }})">
                                                    <i class="ph-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                    @else
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ph-warning-circle fs-1 text-muted mb-3 d-block"></i>
                                            <h6 class="mb-2">No alerts found</h6>
                                            <p class="mb-3">Get started by creating your first alert</p>
                                            <a href="{{ route('admin.alerts.create') }}" class="btn btn-primary">
                                                <i class="ph-plus me-2"></i>Create First Alert
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->

@endsection
@section('center-scripts')
    <script src="{{asset('assets/admin/js/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/vendor/tables/datatables/datatables.min.js')}}"></script>
@endsection

@section('scripts')
    <script>
        window.alertsUrl = '{{ route("admin.alerts.index") }}';
        window.alertEditUrl = '{{ route("admin.alerts.edit", ":id") }}';
        window.alertDeleteUrl = '{{ route("admin.alerts.destroy", ":id") }}';

        function deleteAlert(alertId) {
            if (confirm('Are you sure you want to delete this alert?')) {
                const deleteUrl = window.alertDeleteUrl.replace(':id', alertId);
                const csrfToken = document.querySelector('meta[name="csrf-token"]');

                if (!csrfToken) {
                    alert('CSRF token not found. Please reload the page and try again.');
                    return;
                }

                console.log('Delete URL:', deleteUrl);
                console.log('CSRF Token found:', !!csrfToken);

                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        alert('Alert deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting alert: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting alert: ' + error.message);
                });
            }
        }

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
                        alert('Error toggling alert status: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error toggling alert status');
                });
            }
        }

        // Initialize DataTable
        $(document).ready(function() {
            $('.datatable-column-search-inputs').DataTable({
                columnDefs: [
                    {
                        orderable: false,
                        targets: [7],
                        className: 'text-center'
                    },
                    {
                        targets: [0], // ID column
                        width: '60px',
                        className: 'text-center'
                    },
                    {
                        targets: [2, 3, 5], // Category, Priority, Status columns
                        className: 'text-center'
                    },
                    {
                        targets: [4], // Description column
                        width: '200px'
                    },
                    {
                        targets: [6], // Created Date column
                        width: '120px'
                    }
                ],
                pageLength: 15,
                responsive: true,
                order: [[0, 'desc']], // Sort by ID descending by default
                language: {
                    search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': document.dir == "rtl" ? '&larr;' : '&rarr;',
                        'previous': document.dir == "rtl" ? '&rarr;' : '&larr;'
                    },
                    emptyTable: "No alerts available",
                    zeroRecords: "No matching alerts found"
                },
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-5"i><"col-sm-7"p>>'
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
