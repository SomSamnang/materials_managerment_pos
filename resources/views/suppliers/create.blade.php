@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-truck text-primary me-2"></i>{{ __('Create New Supplier') }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            {{-- Name --}}
                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('Company Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="{{ __('Enter company name...') }}">
                            </div>

                            {{-- Contact Person --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('Contact Person') }}</label>
                                <input type="text" name="contact_person" class="form-control" placeholder="{{ __('Contact person name...') }}">
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('Phone') }}</label>
                                <input type="text" name="phone" class="form-control" placeholder="012 345 678">
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('Email') }}</label>
                                <input type="email" name="email" class="form-control" placeholder="example@email.com">
                            </div>

                            {{-- Address --}}
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('Address') }}</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="{{ __('Address...') }}"></textarea>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary me-2">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection