{{-- Print Card Modal --}}
<div class="modal fade" id="printCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">{{ __('Staff ID Card Preview') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column flex-md-row justify-content-center align-items-center p-4 gap-4">
                {{-- Include Card Template for Modal (user=null) --}}
                @include('users.card', ['user' => null])
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" onclick="printSingleCard()">
                    <i class="bi bi-printer me-1"></i> {{ __('Print Card') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Printable Area for All Cards --}}
<div id="printable-cards" class="d-none">
    @foreach($users->sortByDesc('id') as $user)
        @include('users.card', ['user' => $user])
    @endforeach
</div>

{{-- Include Card Styles --}}
@include('users.card_styles')

<script>
    function printAllCards() {
        document.body.classList.add('printing-all-cards');
        window.print();
        // Remove class after print dialog closes (works in most modern browsers)
        setTimeout(() => {
            document.body.classList.remove('printing-all-cards');
        }, 1000);
    }
    
    // Listen for afterprint to be sure
    window.addEventListener("afterprint", (event) => {
        document.body.classList.remove('printing-all-cards');
    });

    function printSingleCard() {
        window.print();
    }

    function openPrintModal(button) {
        var name = button.getAttribute('data-name');
        var role = button.getAttribute('data-role');
        var photo = button.getAttribute('data-photo');
        var id = button.getAttribute('data-id');
        var email = button.getAttribute('data-email');
        var phone = button.getAttribute('data-phone');
        var joined = button.getAttribute('data-joined');
        var status = button.getAttribute('data-status');
        var profileUrl = button.getAttribute('data-url');

        document.getElementById('card-name').innerText = name;
        document.getElementById('card-role').innerText = role;
        document.getElementById('card-id').innerText = String(id).padStart(4, '0');
        document.getElementById('card-email').innerText = email;
        document.getElementById('card-phone').innerText = phone;
        document.getElementById('card-joined').innerText = joined;
        document.getElementById('card-status').innerText = status;
        document.getElementById('card-photo').src = photo;
        document.getElementById('card-qr').src = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent(profileUrl);

        // Customize colors based on role
        var header = document.getElementById('card-header-bg');
        var badge = document.getElementById('card-role');
        var icons = document.querySelectorAll('#printCardModal .info-icon');
        var termsHeader = document.getElementById('card-terms-header');

        if (role.toLowerCase() === 'admin') {
            header.style.background = 'linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%)'; // Red for Admin
            badge.className = 'id-card-role badge bg-danger mb-4 shadow-sm';
            if(termsHeader) termsHeader.className = 'fw-bold text-uppercase mb-3 text-danger';
            icons.forEach(icon => {
                icon.style.color = '#dc3545';
                icon.style.background = '#fde8e8';
            });
        } else {
            header.style.background = 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)'; // Default Blue
            badge.className = 'id-card-role badge bg-primary mb-4 shadow-sm';
            if(termsHeader) termsHeader.className = 'fw-bold text-uppercase mb-3 text-primary';
            icons.forEach(icon => {
                icon.style.color = '#3182ce';
                icon.style.background = 'rgba(66, 153, 225, 0.1)';
            });
        }
        
        var myModal = new bootstrap.Modal(document.getElementById('printCardModal'));
        myModal.show();
    }
</script>