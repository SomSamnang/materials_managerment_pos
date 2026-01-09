@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-cog me-2 text-primary"></i>{{ __('System Settings') }}</h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="exchange_rate" class="form-label fw-bold">{{ __('Exchange Rate (1 USD = ? KHR)') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">1 USD =</span>
                                <input type="number" class="form-control @error('exchange_rate') is-invalid @enderror" 
                                       id="exchange_rate" name="exchange_rate" 
                                       value="{{ old('exchange_rate', $exchangeRate) }}" required>
                                <span class="input-group-text bg-light">៛</span>
                                @error('exchange_rate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-text text-muted">{{ __('Set the exchange rate for calculating prices shown in Riel in the system.') }}</div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection