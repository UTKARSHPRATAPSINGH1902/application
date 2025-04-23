{{-- @extends('applayout.master')

@section('title', 'Subscribers List')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="container mt-5">
    <h3 class="mb-4">Subscriber List</h3>
    <table id="subscriberTable" class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Package</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subscribers as $subscriber)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $subscriber->name }}</td>
                <td>{{ $subscriber->email }}</td>
                <td>{{ $subscriber->phone }}</td>
                <td>{{ $subscriber->package->name ?? 'N/A' }}</td>
                <td>
                    <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $subscriber->id }}">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#subscriberTable').DataTable();

        $('.deleteBtn').click(function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/subscribers/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire('Deleted!', response.message, 'success')
                                .then(() => location.reload());
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush --}}
@extends('applayout.master')

@section('title', 'Subscribers List')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="container mt-5">
    <h3 class="mb-4">Subscriber List</h3>
    <table id="subscriberTable" class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Package</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subscribers as $subscriber)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $subscriber->name }}</td>
                <td>{{ $subscriber->email }}</td>
                <td>{{ $subscriber->phone }}</td>
                <td>{{ $subscriber->package->name ?? 'N/A' }}</td>
                <td>
                    <button class="btn btn-sm btn-primary editBtn" data-id="{{ $subscriber->id }}">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $subscriber->id }}">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{-- class="btn btn-sm btn-danger deleteBtn" --}}
{{-- class="btn btn-sm btn-primary editBtn"  --}}
{{-- Modal for Edit --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editForm">
      @csrf
      <input type="hidden" id="edit_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Subscriber</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label for="edit_name" class="form-label">Name</label>
            <input type="text" id="edit_name" name="name" class="form-control" required>
          </div>
          <div class="mb-2">
            <label for="edit_email" class="form-label">Email</label>
            <input type="email" id="edit_email" name="email" class="form-control" required>
          </div>
          <div class="mb-2">
            <label for="edit_phone" class="form-label">Phone</label>
            <input type="text" id="edit_phone" name="phone" class="form-control" required>
          </div>
          {{-- You can add dropdown for package if needed --}}
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#subscriberTable').DataTable();

        // Delete
        $('.deleteBtn').click(function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the subscriber.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/subscribers/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire('Deleted!', response.message, 'success')
                                .then(() => location.reload());
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });

        // Edit
        $('.editBtn').click(function () {
            const id = $(this).data('id');
            $.get(`/subscribers/${id}/edit`, function (data) {
                $('#edit_id').val(data.id);
                $('#edit_name').val(data.name);
                $('#edit_email').val(data.email);
                $('#edit_phone').val(data.phone);
                $('#editModal').modal('show');
            });
        });

        // Handle Update (AJAX patch to be created on server)
        $('#editForm').submit(function (e) {
            e.preventDefault();
            const id = $('#edit_id').val();
            $.ajax({
                url: `/subscribers/${id}`,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $('#edit_name').val(),
                    email: $('#edit_email').val(),
                    phone: $('#edit_phone').val()
                },
                success: function (res) {
                    $('#editModal').modal('hide');
                    Swal.fire('Updated!', 'Subscriber info updated.', 'success')
                        .then(() => location.reload());
                },
                error: function () {
                    Swal.fire('Error!', 'Update failed.', 'error');
                }
            });
        });
    });
</script>
@endpush

