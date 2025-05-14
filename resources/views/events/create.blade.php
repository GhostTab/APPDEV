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
                            <h2 class="fw-bold mb-2" style="color: #2d3748;">Post a New Event</h2>
                            <p class="text-muted">Fill in the details below to create your event.</p>
                        </div>
                        
                        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium">Event Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Enter event title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-medium">Category</label>
                                    <select class="form-select @error('category') is-invalid @enderror" name="category" required>
                                        <option value="">Select a category</option>
                                        <option value="festivals" {{ old('category') == 'festivals' ? 'selected' : '' }}>🎉 Festivals</option>
                                        <option value="concerts" {{ old('category') == 'concerts' ? 'selected' : '' }}>🎵 Concerts & Shows</option>
                                        <option value="conferences" {{ old('category') == 'conferences' ? 'selected' : '' }}>🎤 Conferences & Seminars</option>
                                        <option value="workshops" {{ old('category') == 'workshops' ? 'selected' : '' }}>📚 Workshops & Trainings</option>
                                        <option value="sports" {{ old('category') == 'sports' ? 'selected' : '' }}>⚽ Sports & Fitness Events</option>
                                        <option value="fairs" {{ old('category') == 'fairs' ? 'selected' : '' }}>🎪 Fairs & Expositions</option>
                                        <option value="community" {{ old('category') == 'community' ? 'selected' : '' }}>🤝 Community & Advocacy Events</option>
                                        <option value="competitions" {{ old('category') == 'competitions' ? 'selected' : '' }}>🏆 Competitions & Tournaments</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label small fw-medium">Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}" required>
                                    </div>
                                    @error('date')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label small fw-medium">Time</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        <input type="time" class="form-control @error('time') is-invalid @enderror" name="time" value="{{ old('time') }}" required>
                                    </div>
                                    @error('time')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-medium">Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror" name="location" placeholder="Event location" value="{{ old('location') }}" required>
                                    </div>
                                    @error('location')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-medium">Event Image</label>
                                    <div class="drop-zone @error('image') is-invalid @enderror">
                                        <input type="file" class="drop-zone__input" name="image" accept="image/*" id="imageInput" hidden>
                                        <div class="drop-zone__content text-center">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <p class="mb-0">Drag and drop your image here or</p>
                                            <button type="button" class="btn btn-link p-0">browse files</button>
                                        </div>
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <div id="imagePreview" class="mt-3 d-none">
                                        <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-medium">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Describe your event (50-500 characters)" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-5 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 py-2">
                                        <i class="fas fa-paper-plane me-2"></i>Publish Event
                                    </button>
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
.form-control, .form-select {
    padding: 0.625rem 1rem;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.input-group-text {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
}

.form-label {
    margin-bottom: 0.5rem;
    color: #4b5563;
    font-weight: 500;
}

.card {
    background: #fff;
    transition: all 0.3s ease;
}

.btn {
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

textarea {
    resize: none;
}

.drop-zone {
    border: 2px dashed #e2e8f0;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    transition: all 0.2s ease;
    cursor: pointer;
}

.drop-zone:hover {
    border-color: #3b82f6;
    background-color: #f8fafc;
}

.drop-zone__content {
    color: #64748b;
}

.drop-zone__content i {
    color: #3b82f6;
}

.img-thumbnail {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.is-invalid:focus {
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
}

.drop-zone.is-invalid {
    border-color: #dc3545 !important;
}

.drop-zone.is-invalid .drop-zone__content {
    color: #dc3545;
}

.drop-zone.is-invalid .drop-zone__content i {
    color: #dc3545;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.querySelector('.drop-zone');
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('imagePreview');
    const img = preview.querySelector('img');
    
    dropZone.addEventListener('click', () => input.click());
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drop-zone--over');
    });
    
    ['dragleave', 'dragend'].forEach(type => {
        dropZone.addEventListener(type, () => {
            dropZone.classList.remove('drop-zone--over');
        });
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            updateThumbnail(e.dataTransfer.files[0]);
        }
        
        dropZone.classList.remove('drop-zone--over');
    });
    
    input.addEventListener('change', (e) => {
        if (input.files.length) {
            updateThumbnail(input.files[0]);
        }
    });
    
    function updateThumbnail(file) {
        if (file) {
            const reader = new FileReader();
            
            reader.onload = (e) => {
                img.src = e.target.result;
                preview.classList.remove('d-none');
            }
            
            reader.readAsDataURL(file);
        }
    }
});
</script>
@endsection