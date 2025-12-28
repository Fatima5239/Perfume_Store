@extends('admin.layouts.app')

@section('title', 'Add New Gift Item')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Gift Item</h1>
        <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Add New Item</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="e.g., Red Velvet Gift Box"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               id="price" 
                                               name="price" 
                                               step="0.01" 
                                               min="0" 
                                               value="{{ old('price') }}" 
                                               placeholder="0.00"
                                               required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Describe the item...">{{ old('description') }}</textarea>
                            <small class="text-muted">Optional. For gift packages, list what's included.</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Item Image</label>
                                    <input type="file" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           id="image" 
                                           name="image"
                                           accept="image/*"
                                           onchange="previewImage(event)">
                                    <small class="text-muted">Optional. Max 2MB. Recommended: 600x600 pixels</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="hidden" {{ old('status') == 'hidden' ? 'selected' : '' }}>
                                            Hidden
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image Preview</label>
                            <div id="imagePreview" class="border rounded p-3 text-center" style="min-height: 150px;">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p class="mb-0">No image selected</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp_message" class="form-label">Custom WhatsApp Message</label>
                            <textarea class="form-control @error('whatsapp_message') is-invalid @enderror" 
                                      id="whatsapp_message" 
                                      name="whatsapp_message" 
                                      rows="2"
                                      placeholder="Leave empty for auto-generated message">{{ old('whatsapp_message') }}</textarea>
                            <small class="text-muted">
                                Auto-generated: <span id="whatsappPreview" class="text-primary fw-bold"></span>
                            </small>
                            @error('whatsapp_message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #imagePreview {
        background-color: #f8f9fa;
    }
    #imagePreview img {
        max-width: 100%;
        max-height: 200px;
        object-fit: contain;
    }
</style>
@endpush

@push('scripts')
<script>
    // Image preview
    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="img-fluid" alt="Preview">`;
            }
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = `
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="text-muted">
                        <i class="fas fa-image fa-3x mb-2"></i>
                        <p class="mb-0">No image selected</p>
                    </div>
                </div>
            `;
        }
    }

    // WhatsApp message preview
    function updateWhatsAppPreview() {
        const name = document.getElementById('name').value || '[Item Name]';
        const price = document.getElementById('price').value || '0.00';
        const customMessage = document.getElementById('whatsapp_message').value;
        
        if (customMessage) {
            document.getElementById('whatsappPreview').textContent = customMessage;
        } else {
            document.getElementById('whatsappPreview').textContent = 
                `Hello! I want ${name} for $${parseFloat(price).toFixed(2)}`;
        }
    }

    // Update preview on input
    document.getElementById('name').addEventListener('input', updateWhatsAppPreview);
    document.getElementById('price').addEventListener('input', updateWhatsAppPreview);
    document.getElementById('whatsapp_message').addEventListener('input', updateWhatsAppPreview);

    // Initial preview
    updateWhatsAppPreview();
</script>
@endpush