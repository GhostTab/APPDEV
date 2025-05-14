@extends('layouts.admin')

@section('content')
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Event Management</h2>
            <p class="text-muted mb-0">View and manage all events</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Events</li>
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
                        <th class="fw-semibold">Title</th>
                        <th class="fw-semibold">Organizer</th>
                        <th class="fw-semibold">Date</th>
                        <th class="fw-semibold">Category</th>
                        <th class="fw-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td class="fw-medium">{{ $event->title }}</td>
                        <td>{{ $event->user->name }}</td>
                        <td>{{ $event->date->format('M d, Y') }}</td>
                        <td>{{ ucfirst($event->category) }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm px-2" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-warning btn-sm px-2" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($event->status === 'removed')
                                    <form action="{{ route('admin.events.restore', $event) }}" method="POST" class="d-inline" 
                                          data-confirm="Are you sure you want to restore this event to public view?">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm px-2" title="Restore">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.events.remove', $event) }}" method="POST" class="d-inline" 
                                          data-confirm="Are you sure you want to remove this event from public view?">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning btn-sm px-2" title="Remove">
                                            <i class="bi bi-eye-slash"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline" 
                                      data-confirm="Are you sure you want to delete this event? This action cannot be undone.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm px-2" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
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
    .btn {
        padding: 0.5rem;
        font-weight: 500;
        border: none;
        transition: all 0.3s ease;
    }
    .btn:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .btn-sm {
        padding: 0.375rem;
        font-size: 0.875rem;
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
                searchPlaceholder: "Search events...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ events",
                infoEmpty: "Showing 0 to 0 of 0 events",
                infoFiltered: "(filtered from _MAX_ total events)"
            },
            order: [[2, 'asc']] // Sort by date by default
        });
    });
</script>
@endpush
@endsection