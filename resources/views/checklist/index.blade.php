@extends('applayout.master')

@section('title', 'Checklist Management')

@section('style')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .card { border-radius: 10px; }
        .btn i { margin-right: 4px; }
        .modal-content { border-radius: 8px; }
        .modal-header { border-bottom: none; }
    </style>
@endsection

@section('content')
    <div class="container mt-3">
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Checklist List</h4>
                <button type="button" class="btn btn-success" id="addChecklist">
                    <i class="fas fa-plus"></i> Add Checklist
                </button>
            </div>

            <div class="card-body">
                <table class="table table-striped table-bordered" id="checklistTable">
                    <thead class="thead-dark">
                    <tr>
                        <th>S.No</th>
                        <th>Checklist Title</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="checklistModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="checklistForm">
                @csrf
                <input type="hidden" id="checklist_id">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="checklistModalTitle">
                            <i class="fas fa-list-check"></i> Checklist Form
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Checklist Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="checklistSubmitBtn" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Checklist
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            let table = $('#checklistTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('checklist.list') }}",
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title' },
                    { data: 'actions', orderable: false, searchable: false },
                ]
            });

            $('#addChecklist').click(function () {
                $('#checklistForm')[0].reset();
                $('#checklist_id').val('');
                $('#checklistModalTitle').html('<i class="fas fa-list-check"></i> Add Checklist');
                $('#checklistSubmitBtn').html('<i class="fas fa-save"></i> Add Checklist');
                $('#checklistModal').modal('show');
            });

            $('#checklistForm').on('submit', function (e) {
                e.preventDefault();
                $('#checklistSubmitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
                let formData = new FormData(this);
                let id = $('#checklist_id').val();
                let url = id ? "{{ route('checklist.update', ':id') }}".replace(':id', id) : "{{ route('checklist.store') }}";

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        $('#checklistModal').modal('hide');
                        table.ajax.reload();
                        $('#checklistForm')[0].reset();
                        $('#checklistSubmitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Add Checklist');
                        Swal.fire({ icon: 'success', title: res.status, text: res.message });
                    },
                    error: function (xhr) {
                        let row = '';
                        if (xhr.responseJSON?.errors) {
                            $.each(xhr.responseJSON.errors, function (key, value) {
                                row += `<li>${key}: ${value}</li>`;
                            });
                            Swal.fire({ icon: 'error', title: 'Validation Error', html: `<ul>${row}</ul>` });
                        } else {
                            Swal.fire({ icon: 'error', title: xhr.responseJSON?.status || 'Error', text: xhr.responseJSON?.message || 'Something went wrong!' });
                        }
                        $('#checklistSubmitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Add Checklist');
                    }
                });
            });

            $(document).on('click', '.editBtn', function () {
                let id = $(this).data('id');
                $.get("{{ route('checklist.edit', ':id') }}".replace(':id', id), function (res) {
                    $('#checklistForm')[0].reset();
                    $('#checklist_id').val(res.data.id);
                    $('#title').val(res.data.title);
                    $('#checklistModalTitle').html('<i class="fas fa-edit"></i> Edit Checklist');
                    $('#checklistSubmitBtn').html('<i class="fas fa-save"></i> Update Checklist');
                    $('#checklistModal').modal('show');
                });
            });

            $(document).on('click', '.deleteBtn', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "This checklist will be deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('checklist.delete', ':id') }}".replace(':id', id),
                            type: 'DELETE',
                            data: { _token: "{{ csrf_token() }}" },
                            success: function (res) {
                                table.ajax.reload();
                                Swal.fire({ icon: 'success', title: res.status, text: res.message });
                            },
                            error: function (xhr) {
                                Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Something went wrong!' });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
