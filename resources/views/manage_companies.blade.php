@extends('layouts.app')
@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="#companyUpdateCreateModal" data-toggle="modal" class="btn btn-primary btn-sm"><i
                                class="fa fa-plus"></i> Create new company</a>
                    </div>
                    <div class="card-body">
                        <table id="table1" class="table table-bordered w-100">
                            <thead>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Website</th>
                                <th>Tools</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="companyUpdateCreateModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><b>Create new company</b></h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form" method="POST" action="{{ route('createOrUpdateCompany') }}"
                        enctype="multipart/form-data" id="frmCreateOrUpdateCompany">
                        @csrf
                        <input type="hidden" name="formType" id="formType" value="new">
                        <input type="hidden" name="id" id="companyId">
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="website" class="col-sm-3 col-form-label">Website</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="website" name="website" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="companyLogo" class="col-sm-3 col-form-label">Logo</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" id="companyLogo" name="companyLogo">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i>
                        Close</button>
                    <button type="submit" class="btn btn-primary" name="add"><i class="fa fa-save"></i>
                        Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#frmCreateOrUpdateCompany').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission
            console.log('test');
            // Serialize the form data
            var formData = new FormData($(this)[0]);
            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: 'createOrUpdateCompany', // Adjust the URL based on your route
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type
                beforeSend: function() {

                },
                success: function(response) {
                    console.log(response);
                    if (response == 'success') {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                        });
                        window.location.href = "{{ route('manage_companies') }}";

                    } else {
                        Swal.fire({
                            title: 'Error!',
                            html: response,
                            icon: 'error',
                        });
                    }
                },
                error: function(error) {
                    var messsage = JSON.parse(error.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: messsage.message,
                        icon: 'error',
                    });
                }
            });
        });
        $(document).on('click', '.btnDelete', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '../api/deleteCompany',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            console.log(response);
                            var response = JSON.parse(response);
                            if (response.statusCode == 200) {
                                Swal.fire('Deleted!', 'Your item has been deleted.', 'success');
                                location.reload();
                            } else {
                                Swal.fire("Can't delete!",
                                    'This item is used in other transactions!.', 'warning');
                            }
                        }
                    });
                }
            });
        });
        $(document).on('click', '.btnEdit', function() {
            var id = $(this).data('id');
            console.log('id=' + id);
            getRow(id);
        });
        $(document).ready(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                responsive: true,
                ajax: {
                    url: "{{ route('companiesPaginate') }}",
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'website',
                        name: 'website'
                    },
                    {
                        data: 'id',
                        render: function(data, type, full, meta) {
                            return '<button class="btn btn-info mr-3 btnEdit" data-id="' + data +
                                '">Edit</button><button class="btn btn-danger btnDelete" data-id="' +
                                data + '">Delete</button>';
                        }
                    }
                ],
            });
            $('#companyUpdateCreateModal').on('hidden.bs.modal', function() {
                // Reset the form fields
                $('#frmCreateOrUpdateCompany')[0].reset();
            });
        });

        function getRow(id) {
            $.ajax({
                type: 'POST',
                url: '../api/getCompanyById',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    $('.modal-title').html('Update');
                    $('#formType').val('edit');
                    $('#companyId').val(response.id);
                    $('#name').val(response.name);
                    $('#website').val(response.website);
                    $('#email').val(response.email);
                    $('#companyUpdateCreateModal').modal('show');
                }
            });
        }
    </script>
@endsection
