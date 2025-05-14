<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tacloban Events Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a237e;
            --primary-light: #283593;
            --secondary-color: #f8f9fa;
            --text-color: #2d3748;
            --text-muted: #6c757d;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
        }
        
        .sidebar {
            width: 280px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-light) 100%);
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        
        .nav-link {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
            color: white;
        }
        
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .main-content {
            transition: all 0.3s ease;
            padding: 2rem;
            margin-left: 280px;
            width: calc(100% - 280px);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .table {
            border-radius: 1rem;
            overflow: hidden;
        }
        
        .table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 0.5rem;
        }
        
        .admin-header {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar text-white">
            <div class="p-4">
                <h4 class="mb-4 fw-bold d-flex align-items-center">
                    <i class="bi bi-shield-lock me-2"></i>
                    Admin Panel
                </h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.organizer-applications') }}" class="nav-link {{ Request::routeIs('admin.organizer-applications') ? 'active' : '' }}">
                            <i class="bi bi-person-plus me-2"></i> Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.organizers') }}" class="nav-link {{ Request::routeIs('admin.organizers') ? 'active' : '' }}">
                            <i class="bi bi-people me-2"></i> Organizers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.events') }}" class="nav-link {{ Request::routeIs('admin.events') ? 'active' : '' }}">
                            <i class="bi bi-calendar-event me-2"></i> Events
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a href="{{ route('home') }}" class="nav-link text-white-50">
                            <i class="bi bi-house me-2"></i> Back to Site
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            @yield('content')
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="confirmationModalTitle">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmationModalMessage" class="mb-0"></p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmationModalConfirm">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    @stack('styles')
    @stack('scripts')

    <script>
        // Confirmation Modal Handler
        function showConfirmationModal(message, callback) {
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            document.getElementById('confirmationModalMessage').textContent = message;
            
            const confirmButton = document.getElementById('confirmationModalConfirm');
            const oldClickHandler = confirmButton.onclick;
            confirmButton.onclick = function() {
                modal.hide();
                if (callback) callback();
            };
            
            modal.show();
        }

        // Handle form submissions with confirmation
        document.addEventListener('submit', function(e) {
            if (e.target.hasAttribute('data-confirm')) {
                e.preventDefault();
                const message = e.target.getAttribute('data-confirm');
                showConfirmationModal(message, () => e.target.submit());
            }
        });
    </script>

    <style>
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .modal-header {
            padding: 1.5rem 1.5rem 0.5rem;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-footer {
            padding: 0.5rem 1.5rem 1.5rem;
        }
        .btn-close {
            padding: 0.5rem;
            margin: -0.5rem -0.5rem -0.5rem auto;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>