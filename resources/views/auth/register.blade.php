@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Register for Farm Market</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="registrationForm">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label">Confirm Password</label>
                                <input id="password-confirm" type="password" class="form-control" 
                                       name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="role" class="form-label">Account Type</label>
                                <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required>
                                    <option value="">Select Account Type</option>
                                    <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                                    <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                                    <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Driver</option>
                                </select>
                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" class="form-control @error('address') is-invalid @enderror" 
                                      name="address" required rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                            <!-- Hidden fields for coordinates -->
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', -1.2921) }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', 36.8219) }}">
                            
                            <div class="mt-2">
                                <button type="button" id="getLocation" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-map-marker-alt"></i> Use My Current Location
                                </button>
                                <small class="text-muted ms-2" id="locationStatus">Click to detect your location for better delivery estimates</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-success w-100">
                                Register
                            </button>
                        </div>

                        <div class="text-center">
                            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const getLocationBtn = document.getElementById('getLocation');
    const locationStatus = document.getElementById('locationStatus');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const addressInput = document.getElementById('address');

    getLocationBtn.addEventListener('click', function() {
        locationStatus.textContent = 'Detecting your location...';
        getLocationBtn.disabled = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    latitudeInput.value = lat;
                    longitudeInput.value = lng;
                    
                    // Reverse geocoding to get address
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.display_name) {
                                addressInput.value = data.display_name;
                            }
                            locationStatus.textContent = 'Location detected successfully!';
                            getLocationBtn.disabled = false;
                        })
                        .catch(error => {
                            locationStatus.textContent = 'Location detected but address not found';
                            getLocationBtn.disabled = false;
                        });
                },
                function(error) {
                    console.error('Geolocation error:', error);
                    locationStatus.textContent = 'Unable to detect location. Using default coordinates.';
                    getLocationBtn.disabled = false;
                }
            );
        } else {
            locationStatus.textContent = 'Geolocation is not supported by your browser.';
            getLocationBtn.disabled = false;
        }
    });
});
</script>
@endpush
@endsection