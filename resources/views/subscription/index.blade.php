
@extends('applayout.master')

@section('title', 'Package Management')

@push('styles')
<!-- Bootstrap 5 & Other UI Libraries -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
{{-- <style>
    .opacity-25 {
        opacity: 0.25;
        transition: opacity 0.3s ease;
    }
</style> --}}


@endpush

@section('content')
<div class="container mt-4">
    <div class="row">
        @foreach ($packages as $package)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 text-center border-primary">
                {{-- <div class="card shadow-sm h-100 text-center border-primary package-card" data-package="{{ $package->id }}"> --}}

                <div class="card-header bg-primary text-white">
                    <h4 class="my-0">{{ $package->name }}</h4>
                </div>
                <div class="card-body">
                    <h2 class="card-title pricing-card-title">
                        ₹{{ $package->monthly_price }} <small class="text-muted">/mo</small>
                    </h2>
                    <h5 class="text-muted mb-3">or ₹{{ $package->annual_price }} /year</h5>
                    <ul class="list-unstyled mt-3 mb-4">
                        <li><strong>Employees:</strong> {{ $package->max_employees }}</li>
                        <li><strong>Storage:</strong> {{ $package->storage_size }} {{ $package->storage_unit }}</li>
                        <li><strong>Checklist:</strong> {{ implode(', ', $package->checklists->pluck('title')->toArray()) }}</li>
                    </ul>
                    <button class="btn btn-outline-primary subscribeBtn" data-package="{{ $package->id }}">
                        Subscribe
                    </button>
                    {{-- <button class="btn btn-outline-primary" onclick="event.stopPropagation();">
                        Subscribe
                    </button> --}}
                    
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Subscription Modal -->
<div class="modal fade" id="subscribeModal" tabindex="-1" aria-labelledby="subscribeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-primary">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="subscribeModalLabel">Subscribe Now</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="subscribeForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="package_id" id="package_id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" autocomplete="name" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email"  id="email" autocomplete="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" id="phone" autocomplete="phone" class="form-control" name="phone" required>
                    </div>
                    <div id="subscribeMsg" class="text-success fw-bold"></div>
                    <div id="subscribeError" class="text-danger fw-bold"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Sign Up Now</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery, Bootstrap & AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        // Open modal on Subscribe button click
        $('.subscribeBtn').click(function () {
            const packageId = $(this).data('package');
            $('#package_id').val(packageId);
    //         $('.subscribeBtn').closest('.card').addClass('opacity-25'); // Fade all
    // $(this).closest('.card').removeClass('opacity-25'); // Highlight clicked one
    //         // console.log("Subscribe clicked for package: ", packageId);
            $('#subscribeModal').modal('show');
            $('#subscribeForm')[0].reset();
            $('#subscribeMsg').html('');
            $('#subscribeError').html('');
            // When a package card is clicked
// $('.package-card').click(function () {
//     const $clickedCard = $(this);
//     const packageId = $clickedCard.data('package');

//     // Fade all cards
//     $('.package-card').addClass('opacity-25');
//     // Highlight only the selected one
//     $clickedCard.removeClass('opacity-25');

//     // Set form input and open modal
//     $('#package_id').val(packageId);
//     $('#subscribeModal').modal('show');
//     $('#subscribeForm')[0].reset();
//     $('#subscribeMsg').html('');
//     $('#subscribeError').html('');
// });

// When the modal is hidden, reset all cards
$('#subscribeModal').on('hidden.bs.modal', function () {
    $('.package-card').removeClass('opacity-25');
});

        });


        // AJAX Subscription
        $('#subscribeForm').submit(function (e) {
            e.preventDefault();
            $('#subscribeMsg').html('');
            $('#subscribeError').html('');

            $.ajax({
                url: "{{ route('subscribe.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.status === 'success') {
                        $('#subscribeMsg').html(response.message);
                        $('#subscribeForm')[0].reset();
                        setTimeout(() => {
                            $('#subscribeModal').modal('hide');
                        }, 1500);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let msg = '';
                        for (let key in errors) {
                            msg += errors[key][0] + '<br>';
                        }
                        $('#subscribeError').html(msg);
                    } else {
                        $('#subscribeError').html('Something went wrong. Please try again.');
                    }
                }
            });
        });
    });

</script>

@endpush
