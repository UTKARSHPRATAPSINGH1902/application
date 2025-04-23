@extends('applayout.master')

@section('title', 'Package Management')

@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.2/dist/css/bootstrap-multiselect.css">



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
            <h4 class="mb-0">Package List</h4>
            <button type="button" class="btn btn-success" id="addPackage">
                <i class="fas fa-plus"></i> Add Package
            </button>
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered" id="packageTable">
                <thead class="thead-dark">
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Monthly Price</th>
                        <th>Annual Price</th>
                        <th>Max Employees</th>
                        <th>Storage</th>
                        <th>Checklist Items</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="packageModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="packageForm">
            @csrf
            <input type="hidden" id="package_id">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-box"></i> Package Form</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group"><label>Name</label><input type="text" class="form-control" name="name" id="name" required></div>
                    <div class="form-group"><label>Monthly Price</label><input type="number" step="0.01" class="form-control" name="monthly_price" id="monthly_price" required></div>
                    <div class="form-group"><label>Annual Price</label><input type="number" step="0.01" class="form-control" name="annual_price" id="annual_price" required></div>
                    <div class="form-group"><label>Max Employees</label><input type="number" class="form-control" name="max_employees" id="max_employees" required></div>
                    <div class="form-group">
                        <label>Storage</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="storage_size" id="storage_size" required>
                            <select class="form-control" name="storage_unit" id="storage_unit">
                                <option value="MB">MB</option>
                                <option value="GB">GB</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group"><label>Description</label><textarea class="form-control" name="description" id="description"></textarea></div>
                    {{-- <div class="form-group">
                        <label>Select Checklists</label>
                        <select class="form-control" name="checklists[]" id="checklists" multiple >
                            @foreach($checklists as $checklist)
                                <option value="{{ $checklist->id }}">{{ $checklist->title }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <label><strong>Select Modules For This Package</strong></label>
                        
                        <div class="mb-2">
                            <input type="checkbox" id="selectAll" class="mr-1">
                            <label for="selectAll">Select All</label>
                        </div>
                    
                        <div class="row">
                            @foreach($checklists as $checklist)
                                <div class="col-md-3 mb-2">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checklist-item" 
                                               id="checklist_{{ $checklist->id }}" 
                                               name="checklists[]" 
                                               value="{{ $checklist->id }}">
                                        <label class="custom-control-label" for="checklist_{{ $checklist->id }}">
                                            {{ $checklist->title }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="packageSubmitBtn" class="btn btn-primary"><i class="fas fa-save"></i> Save Package</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.2/dist/js/bootstrap-multiselect.min.js"></script>

<!-- Select2 CSS -->

{{-- <!-- jQuery (if not already added) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    let table = $('#packageTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('package.list') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'monthly_price' },
            { data: 'annual_price' },
            { data: 'max_employees' },
            { data: 'storage' },
            { data: 'checklists', orderable: false, searchable: false },
            { data: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#addPackage').click(function () {
        $('#packageForm')[0].reset();
        $('#package_id').val('');
        $('#checklists').val([]).trigger('change');
        $('#packageModal .modal-title').html('<i class="fas fa-box"></i> Add Package');
        $('#packageSubmitBtn').html('<i class="fas fa-save"></i> Save Package');
        $('#packageModal').modal('show');
    });

    $('#packageForm').on('submit', function (e) {
        e.preventDefault();
        $('#packageSubmitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        let id = $('#package_id').val();
        let formData = new FormData(this);
        let url = id ? "{{ route('package.update', ':id') }}".replace(':id', id) : "{{ route('package.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                $('#packageModal').modal('hide');
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: res.status, text: res.message });
                $('#packageForm')[0].reset();
                $('#packageSubmitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Package');
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                let list = '';
                if (errors) {
                    $.each(errors, (k, v) => list += `<li>${v}</li>`);
                    Swal.fire({ icon: 'error', title: 'Validation Error', html: `<ul>${list}</ul>` });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong' });
                }
                $('#packageSubmitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Package');
            }
        });
    });

    $(document).on('click', '.editBtn', function () {
        let id = $(this).data('id');
        $.get("{{ route('package.edit', ':id') }}".replace(':id', id), function (res) {
            $('#package_id').val(res.data.id);
            $('#name').val(res.data.name);
            $('#monthly_price').val(res.data.monthly_price);
            $('#annual_price').val(res.data.annual_price);
            $('#max_employees').val(res.data.max_employees);
            $('#storage_size').val(res.data.storage_size);
            $('#storage_unit').val(res.data.storage_unit);
            $('#description').val(res.data.description);
            $('#checklists').val(res.checklists).trigger('change');
            $('#packageModal .modal-title').html('<i class="fas fa-edit"></i> Edit Package');
            $('#packageSubmitBtn').html('<i class="fas fa-save"></i> Update Package');
            $('#packageModal').modal('show');
        });
    });

    $(document).on('click', '.deleteBtn', function () {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'Package will be deleted permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('package.delete', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function (res) {
                        table.ajax.reload();
                        Swal.fire({ icon: 'success', title: res.status, text: res.message });
                    }
                });
            }
        });
    });
});
    // $(document).ready(function() {
    //     $('#checklists').select2({
    //         placeholder: "Select Checklist Items",
    //         allowClear: true,
    //         width: '100%'
    //     });
    // });
    // $(document).ready(function() {
    //     $('#checklists').multiselect({
    //         includeSelectAllOption: true,
    //         enableFiltering: true,
    //         enableCaseInsensitiveFiltering: true,
    //         buttonWidth: '100%',
    //         maxHeight: 300,
    //         nonSelectedText: 'Select Checklists'
    //     });
    // });
    $(document).ready(function () {
        $('#selectAll').click(function () {
            $('.checklist-item').prop('checked', this.checked);
        });

        // Uncheck "Select All" if one is unchecked
        $('.checklist-item').change(function () {
            if ($('.checklist-item:checked').length !== $('.checklist-item').length) {
                $('#selectAll').prop('checked', false);
            } else {
                $('#selectAll').prop('checked', true);
            }
        });
    });
</script>
@endsection
