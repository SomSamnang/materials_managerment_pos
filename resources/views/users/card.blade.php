@php
    $user = $user ?? null;
    $isTemplate = is_null($user);
    
    // Data setup
    $photoUrl = $isTemplate ? '' : $user->profile_photo_url;
    $name = $isTemplate ? 'User Name' : $user->name;
    $role = $isTemplate ? 'ROLE' : ucfirst($user->role);
    $idNumber = $isTemplate ? '0000' : str_pad($user->id, 4, '0', STR_PAD_LEFT);
    $email = $isTemplate ? 'email@example.com' : $user->email;
    $phone = $isTemplate ? '000-000-000' : ($user->phone ?? '-');
    $joined = $isTemplate ? '01 Jan 2024' : $user->created_at->format('d M Y');
    $status = $isTemplate ? 'Active' : ucfirst($user->status);
    $qrUrl = $isTemplate ? '' : 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode(route('users.edit', $user->id));
    
    // Styles setup
    $isAdmin = !$isTemplate && strtolower($user->role) === 'admin';
    $headerGradient = $isAdmin ? 'linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%)' : 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)';
    $badgeClass = $isAdmin ? 'bg-danger' : 'bg-primary';
    $iconColor = $isAdmin ? '#dc3545' : '#3182ce';
    $iconBg = $isAdmin ? '#fde8e8' : 'rgba(66, 153, 225, 0.1)';
    $termsClass = $isAdmin ? 'text-danger' : 'text-primary';
@endphp

<div class="id-card-pair-wrapper">
    <!-- Front Side -->
    <div class="id-card">
        <div class="id-card-bg-pattern"></div>
        <div class="id-card-chip"></div>
        
        <div class="id-card-header" style="background: {{ $isTemplate ? '' : $headerGradient }}" @if($isTemplate) id="card-header-bg" @endif>
            <img src="https://via.placeholder.com/50/ffffff/000000?text=Logo" class="id-card-logo" alt="Logo">
            <div class="text-center z-1">
                <i class="bi bi-box-seam-fill fs-1 mb-1"></i>
                <div class="text-uppercase fw-bold tracking-wider">Material System</div>
            </div>
        </div>

        <div class="id-card-avatar">
            <img src="{{ $photoUrl }}" alt="Profile" @if($isTemplate) id="card-photo" @endif>
        </div>

        <div class="id-card-body">
            <h3 class="id-card-name" @if($isTemplate) id="card-name" @endif>{{ $name }}</h3>
            <div class="id-card-role badge {{ $isTemplate ? 'bg-primary' : $badgeClass }} mb-2 shadow-sm" @if($isTemplate) id="card-role" @endif>{{ $role }}</div>
            
            <div class="id-card-info">
                <div class="info-row">
                    <div class="info-icon" style="color: {{ $iconColor }}; background: {{ $iconBg }}" @if($isTemplate) id="icon-hash" @endif><i class="bi bi-hash"></i></div>
                    <div class="info-content">
                        <small class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">ID Number</small>
                        <div class="fw-bold" @if($isTemplate) id="card-id" @endif>{{ $idNumber }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon" style="color: {{ $iconColor }}; background: {{ $iconBg }}" @if($isTemplate) id="icon-email" @endif><i class="bi bi-envelope"></i></div>
                    <div class="info-content">
                        <small class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Email Address</small>
                        <div class="fw-bold text-truncate" style="max-width: 190px;" @if($isTemplate) id="card-email" @endif>{{ $email }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon" style="color: {{ $iconColor }}; background: {{ $iconBg }}" @if($isTemplate) id="icon-phone" @endif><i class="bi bi-telephone"></i></div>
                    <div class="info-content">
                        <small class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Phone Number</small>
                        <div class="fw-bold" @if($isTemplate) id="card-phone" @endif>{{ $phone }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon" style="color: {{ $iconColor }}; background: {{ $iconBg }}" @if($isTemplate) id="icon-joined" @endif><i class="bi bi-calendar-event"></i></div>
                    <div class="info-content">
                        <small class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Joined Date</small>
                        <div class="fw-bold" @if($isTemplate) id="card-joined" @endif>{{ $joined }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon" style="color: {{ $iconColor }}; background: {{ $iconBg }}" @if($isTemplate) id="icon-status" @endif><i class="bi bi-shield-check"></i></div>
                    <div class="info-content">
                        <small class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Status</small>
                        <div class="fw-bold" @if($isTemplate) id="card-status" @endif>{{ $status }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="id-card-footer">
            <div class="barcode"></div>
            <div class="mt-1 text-muted fw-bold" style="font-size: 0.6rem; letter-spacing: 2px;">AUTHORIZED PERSONNEL</div>
        </div>
    </div>

    <!-- Back Side -->
    <div class="id-card">
        <div class="id-card-bg-pattern"></div>
        <div class="id-card-body d-flex flex-column justify-content-center align-items-center h-100">
            <div class="text-center mb-4 opacity-25">
                <i class="bi bi-box-seam-fill" style="font-size: 5rem;"></i>
            </div>
            <div class="text-center px-4">
                <h6 class="fw-bold text-uppercase mb-3 {{ $isTemplate ? 'text-primary' : $termsClass }}" @if($isTemplate) id="card-terms-header" @endif>Terms & Conditions</h6>
                <p class="text-muted mb-3" style="font-size: 0.75rem; line-height: 1.5;">This card remains the property of Material System. If found, please return to the nearest office.</p>
            </div>
            <div class="mt-auto pb-5 text-center w-100">
                <img src="{{ $qrUrl }}" alt="QR Code" style="width: 100px; height: 100px; object-fit: contain; background: white; padding: 5px; border-radius: 8px;" @if($isTemplate) id="card-qr" @endif>
            </div>
        </div>
    </div>
</div>