@extends('layouts.admin')

@section('content')
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Organizer Applications</h2>
            <p class="text-muted mb-0">Manage event organizer applications</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Applications</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="fw-semibold">Name</th>
                        <th class="fw-semibold">Email</th>
                        <th class="fw-semibold">Experience</th>
                        <th class="fw-semibold">Status</th>
                        <th class="fw-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                    <tr class="align-middle">
                        <td class="fw-medium">
                            <div class="text-truncate" style="max-width: 200px;" title="{{ $application->first_name }} {{ $application->middle_name ? $application->middle_name . ' ' : '' }}{{ $application->last_name }}">
                                {{ $application->first_name }}
                                {{ $application->middle_name ? $application->middle_name . ' ' : '' }}
                                {{ $application->last_name }}
                            </div>
                        </td>
                        <td>{{ $application->email }}</td>
                        <td>
                            <div class="text-truncate" style="max-width: 300px;" title="{{ $application->experience }}">
                                {{ $application->experience }}
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = [
                                    'pending' => 'bg-warning bg-opacity-10 text-warning',
                                    'approved' => 'bg-success bg-opacity-10 text-success',
                                    'rejected' => 'bg-danger bg-opacity-10 text-danger'
                                ][$application->status] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>
                        <td>
                            @if($application->status === 'pending')
                            <div class="d-flex gap-1">
                                <form action="{{ route('admin.organizer-applications.update', ['application' => $application->id]) }}" 
                                      method="POST" class="d-inline"
                                      data-confirm="Are you sure you want to approve this organizer application?">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-success btn-sm px-2">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.organizer-applications.update', ['application' => $application->id]) }}" 
                                      method="POST" class="d-inline"
                                      data-confirm="Are you sure you want to reject this organizer application?">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger btn-sm px-2">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-primary btn-sm px-2" data-bs-toggle="modal" data-bs-target="#applicationModal{{ $application->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($applications as $application)
<div class="modal fade" id="applicationModal{{ $application->id }}" tabindex="-1" aria-labelledby="applicationModalLabel{{ $application->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applicationModalLabel{{ $application->id }}">Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Personal Information</h6>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Full Name</label>
                            <p class="mb-0">{{ $application->first_name }} {{ $application->middle_name ? $application->middle_name . ' ' : '' }}{{ $application->last_name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Email</label>
                            <p class="mb-0">{{ $application->email }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Phone</label>
                            <p class="mb-0">{{ $application->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Application Details</h6>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Status</label>
                            <p class="mb-0">
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Applied Date</label>
                            <p class="mb-0">{{ $application->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="text-muted mb-2">Experience</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $application->experience }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    .card {
        transform: none !important;
        transition: none !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
    .card:hover, .card:focus, .card-body:hover, .card-body:focus {
        transform: none !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
    .dataTables_wrapper .dataTables_length select {
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5rem;
        padding: 0.375rem 0.75rem;
    }
    .modal-content {
        border-radius: 12px;
    }
    .modal-header {
        border-bottom: 1px solid #e9ecef;
    }
    .modal-footer {
        border-top: 1px solid #e9ecef;
    }
    .form-label {
        color: #6c757d;
        font-size: 0.875rem;
    }
    .card.bg-light {
        background-color: #f8f9fa !important;
    }
    .table td {
        white-space: nowrap;
    }
    .table td:last-child {
        white-space: normal;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .card, .card-body {
        transform: none !important;
        transition: none !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
    .card:hover, .card:focus, .card:active,
    .card-body:hover, .card-body:focus, .card-body:active {
        transform: none !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search applications...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ applications",
                infoEmpty: "Showing 0 to 0 of 0 applications",
                infoFiltered: "(filtered from _MAX_ total applications)"
            },
            order: [[3, 'asc']] // Sort by status column by default
        });
    });
</script>
@endpush
@endsection