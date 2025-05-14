@extends('layouts.admin')

@section('content')
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Event Organizers</h2>
            <p class="text-muted mb-0">Manage event organizers' roles</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Organizers</li>
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
                        <th class="fw-semibold">Events Created</th>
                        <th class="fw-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($organizers as $organizer)
                    <tr>
                        <td class="fw-medium">
                            <div class="text-truncate" style="max-width: 200px;" title="{{ $organizer->name }}">
                                {{ $organizer->name }}
                            </div>
                        </td>
                        <td>{{ $organizer->email }}</td>
                        <td>{{ $organizer->events_count ?? 0 }}</td>
                        <td>
                            <form action="{{ route('admin.organizers.demote', $organizer) }}" method="POST" class="d-inline"
                                  data-confirm="Are you sure you want to remove organizer privileges from this user? This action cannot be undone.">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-soft-danger btn-sm">
                                    <i class="bi bi-person-x me-1"></i> Remove Organizer Role
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

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
    .btn-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: none;
    }
    .btn-soft-danger:hover {
        background-color: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }
    .dataTables_wrapper .dataTables_length select {
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5rem;
        padding: 0.375rem 0.75rem;
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
                searchPlaceholder: "Search organizers...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ organizers",
                infoEmpty: "Showing 0 to 0 of 0 organizers",
                infoFiltered: "(filtered from _MAX_ total organizers)"
            },
            order: [[2, 'desc']] // Sort by events count by default
        });
    });
</script>
@endpush
@endsection