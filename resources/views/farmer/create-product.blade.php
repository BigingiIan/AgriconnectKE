<!-- resources/views/farmer/create-product.blade.php -->
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Product</h1>
    <a href="{{ route('farmer.products') }}" class="btn btn-secondary">Back to Products</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card content-card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold">Product Details</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('farmer.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold">Product Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required placeholder="e.g., Fresh Tomatoes">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="category" class="form-label fw-bold">Category</label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="vegetables" {{ old('category') == 'vegetables' ? 'selected' : '' }}>Vegetables</option>
                                <option value="fruits" {{ old('category') == 'fruits' ? 'selected' : '' }}>Fruits</option>
                                <option value="grains" {{ old('category') == 'grains' ? 'selected' : '' }}>Grains</option>
                                <option value="dairy" {{ old('category') == 'dairy' ? 'selected' : '' }}>Dairy</option>
                                <option value="poultry" {{ old('category') == 'poultry' ? 'selected' : '' }}>Poultry</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" required placeholder="Describe your product...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Product Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Upload a clear image of your product (JPEG, PNG, JPG, GIF, max 2MB)
                        </div>
                        <div class="mt-2">
                            <img id="imagePreview" src="#" alt="Image preview" class="img-thumbnail d-none rounded shadow-sm" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="price" class="form-label fw-bold">Price (Ksh)</label>
                            <div class="input-group">
                                <span class="input-group-text">Ksh</span>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="quantity" class="form-label fw-bold">Quantity</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="accepts_bids" name="accepts_bids" 
                                   {{ old('accepts_bids') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="accepts_bids">
                                Accept bids from buyers
                            </label>
                            <div class="form-text">Allow buyers to suggest a price for this product.</div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('farmer.products') }}" class="btn btn-light btn-rounded px-4">Cancel</a>
                        <button type="submit" class="btn btn-success btn-rounded px-5 shadow-sm">List Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card content-card bg-gradient-info text-white border-0">
            <div class="card-header bg-transparent border-bottom-0">
                <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips for Listing</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-check-circle me-2 opacity-75"></i> Use clear and descriptive product names</li>
                    <li class="mb-2"><i class="fas fa-check-circle me-2 opacity-75"></i> Provide detailed descriptions</li>
                    <li class="mb-2"><i class="fas fa-check-circle me-2 opacity-75"></i> Upload high-quality product images</li>
                    <li class="mb-2"><i class="fas fa-check-circle me-2 opacity-75"></i> Set competitive prices</li>
                    <li class="mb-2"><i class="fas fa-check-circle me-2 opacity-75"></i> Enable bids to allow buyers to make offers</li>
                    <li><i class="fas fa-check-circle me-2 opacity-75"></i> Keep your inventory updated</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.addEventListener('load', function() {
                    imagePreview.src = reader.result;
                    imagePreview.classList.remove('d-none');
                });
                
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('d-none');
            }
        });
    });
</script>
@endpush