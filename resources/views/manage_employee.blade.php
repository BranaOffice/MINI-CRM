@extends('layouts.app')
@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="#employeeUpdateCreateModal" data-toggle="modal" class="btn btn-primary btn-sm"><i
                                class="fa fa-plus"></i> Create new employee</a>
                    </div>
                    <div class="card-body">
                        <table id="table1" class="table table-bordered w-100">
                            <thead>
                                <th>First name</th>
                                <th>Last name</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Phone</th>
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
    <div class="modal fade" id="employeeUpdateCreateModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><b>Create new employee</b></h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form" method="POST" action="{{ route('createOrUpdateEmployee') }}"
                        enctype="multipart/form-data" id="frmCreateOrUpdateEmployee">
                        @csrf
                        <input type="hidden" name="formType" id="formType" value="new">
                        <input type="hidden" name="id" id="employeeId">
                        <div class="form-group row">
                            <label for="fname" class="col-sm-3 col-form-label">First name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="fname" name="fname" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lname" class="col-sm-3 col-form-label">Last name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="lname" name="lname" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dropCompanyId" class="col-sm-3 col-form-label">Company</label>
                            <div class="col-sm-9">
                                <select name="dropCompanyId" id="dropCompanyId" class="form-control">
                                    @if ($companies->count() > 0)
                                        @foreach ($companies as $rs)
                                            <option value='{{ $rs->id }}'>
                                                {{ $rs->name }} </option>
                                        @endforeach
                                    @else
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 col-form-label">Phone number</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="phone" name="phone">
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
        $('#frmCreateOrUpdateEmployee').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission
            console.log('test');
            // Serialize the form data
            var formData = new FormData($(this)[0]);
            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: 'createOrUpdateEmployee', // Adjust the URL based on your route
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
                        window.location.href = "{{ route('manage_employees') }}";

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
                        url: '../api/deleteEmployee',
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
                    url: "{{ route('employeesPaginate') }}",
                },
                columns: [{
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        data: 'company_id',
                        name: 'company_id'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
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
            $('#employeeUpdateCreateModal').on('hidden.bs.modal', function() {
                // Reset the form fields
                $('#frmCreateOrUpdateEmployee')[0].reset();
            });
        });

        function getRow(id) {
            $.ajax({
                type: 'POST',
                url: '../api/getEmployeeById',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    $('.modal-title').html('Update');
                    $('#formType').val('edit');
                    $('#employeeId').val(response.id);
                    $('#fname').val(response.first_name);
                    $('#lname').val(response.last_name);
                    $('#dropCompanyId').val(response.company_id);
                    $('#phone').val(response.phone);
                    $('#email').val(response.email);
                    $('#employeeUpdateCreateModal').modal('show');
                }
            });
        }
    </script>
@endsection
