@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Product</h1>
    <a href="{{ route('farmer.products') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Products
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('farmer.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="category" class="form-label">Category *</label>
                            <select class="form-control @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="vegetables" {{ old('category') == 'vegetables' ? 'selected' : '' }}>Vegetables</option>
                                <option value="fruits" {{ old('category') == 'fruits' ? 'selected' : '' }}>Fruits</option>
                                <option value="grains" {{ old('category') == 'grains' ? 'selected' : '' }}>Grains & Cereals</option>
                                <option value="dairy" {{ old('category') == 'dairy' ? 'selected' : '' }}>Dairy Products</option>
                                <option value="poultry" {{ old('category') == 'poultry' ? 'selected' : '' }}>Poultry</option>
                                <option value="livestock" {{ old('category') == 'livestock' ? 'selected' : '' }}>Livestock</option>
                                <option value="herbs" {{ old('category') == 'herbs' ? 'selected' : '' }}>Herbs & Spices</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image *</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" required>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Upload a clear image of your product (JPEG, PNG, JPG, GIF, max 2MB)
                        </div>
                        <div class="mt-2">
                            <img id="imagePreview" src="#" alt="Image preview" class="img-thumbnail d-none" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="price" class="form-label">Price (Ksh) *</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">Quantity *</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="accepts_bids" name="accepts_bids" value="1"
                                   {{ old('accepts_bids') ? 'checked' : '' }}>
                            <label class="form-check-label" for="accepts_bids">
                                Accept bids from buyers
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> List Product
                        </button>
                        <a href="{{ route('farmer.products') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Tips for Listing Products</h6>
            </div>
            <div class="card-body">
                <ul class="small text-muted">
                    <li class="mb-2">Use clear and descriptive product names</li>
                    <li class="mb-2">Provide detailed descriptions including quality and freshness</li>
                    <li class="mb-2">Upload high-quality product images from different angles</li>
                    <li class="mb-2">Set competitive prices based on market rates</li>
                    <li class="mb-2">Enable bids to allow buyers to make offers</li>
                    <li class="mb-2">Keep your inventory updated regularly</li>
                    <li class="mb-2">Be responsive to buyer inquiries and bids</li>
                </ul>
            </div>
        </div>
    </div>
</div>

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
@endsection