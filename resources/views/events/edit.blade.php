@extends('layouts.app')

@section('content')
<div class="min-vh-100 bg-light">
    <div class="bg-primary">
        @include('components.navbar')
    </div>
    
    <div class="container py-5" style="max-width: 1000px;">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-md-5">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold mb-2" style="color: #2d3748;">Edit Event</h2>
                            <p class="text-muted">Update your event details below</p>
                        </div>
                        
                        <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium">Event Title</label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $event->title) }}" placeholder="Enter event title" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-medium">Category</label>
                                    <select class="form-select" name="category" required>
                                        <option value="">Select a category</option>
                                        <option value="festivals" {{ old('category', $event->category) == 'festivals' ? 'selected' : '' }}>🎉 Festivals</option>
                                        <option value="concerts" {{ old('category', $event->category) == 'concerts' ? 'selected' : '' }}>🎵 Concerts & Shows</option>
                                        <option value="conferences" {{ old('category', $event->category) == 'conferences' ? 'selected' : '' }}>🎤 Conferences & Seminars</option>
                                        <option value="workshops" {{ old('category', $event->category) == 'workshops' ? 'selected' : '' }}>📚 Workshops & Trainings</option>
                                        <option value="sports" {{ old('category', $event->category) == 'sports' ? 'selected' : '' }}>⚽ Sports & Fitness Events</option>
                                        <option value="fairs" {{ old('category', $event->category) == 'fairs' ? 'selected' : '' }}>🎪 Fairs & Expositions</option>
                                        <option value="community" {{ old('category', $event->category) == 'community' ? 'selected' : '' }}>🤝 Community & Advocacy Events</option>
                                        <option value="competitions" {{ old('category', $event->category) == 'competitions' ? 'selected' : '' }}>🏆 Competitions & Tournaments</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-medium">Description</label>
                                    <textarea class="form-control" name="description" rows="4" placeholder="Describe your event" required>{{ old('description', $event->description) }}</textarea>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label small fw-medium">Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        <input type="date" class="form-control" name="date" value="{{ old('date', \Carbon\Carbon::parse($event->date)->format('Y-m-d')) }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label small fw-medium">Time</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        <input type="time" class="form-control" name="time" value="{{ old('time', \Carbon\Carbon::parse($event->time)->format('H:i')) }}" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-medium">Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" name="location" value="{{ old('location', $event->location) }}" placeholder="Event location" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-medium">Event Image</label>
                                    @if($event->image)
                                        <div class="mb-3">
                                            <img src="{{ asset($event->image) }}" alt="Current event image" class="img-thumbnail" style="max-height: 200px;">
                                        </div>
                                    @endif
                                    <div class="drop-zone">
                                        <input type="file" class="drop-zone__input" name="image" accept="image/*" id="imageInput" hidden>
                                        <div class="drop-zone__content text-center">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <p class="mb-0">Drag and drop your image here or</p>
                                            <button type="button" class="btn btn-link p-0">browse files</button>
                                        </div>
                                    </div>
                                    <div id="imagePreview" class="mt-3 d-none">
                                        <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('events.manage') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Back to Events
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Update Event
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.drop-zone {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.drop-zone:hover {
    border-color: #3b82f6;
    background-color: rgba(59, 130, 246, 0.05);
}

.drop-zone__content {
    color: #6c757d;
}

.btn-primary {
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

.btn-outline-secondary {
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.2);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.querySelector('.drop-zone');
    const fileInput = document.querySelector('.drop-zone__input');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = imagePreview.querySelector('img');

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#3b82f6';
        dropZone.style.backgroundColor = 'rgba(59, 130, 246, 0.05)';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '#dee2e6';
        dropZone.style.backgroundColor = 'transparent';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#dee2e6';
        dropZone.style.backgroundColor = 'transparent';

        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            fileInput.files = e.dataTransfer.files;
            displayImage(file);
        }
    });

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            displayImage(file);
        }
    });

    function displayImage(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            imagePreview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection 