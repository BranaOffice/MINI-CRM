@extends('layouts.app')
@section('contents')
    <div class="bg-white p-3 customDiv mb-3">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#userModal">
            Create user
        </button>

        <!-- Modal -->
        <div class="modal fade" id="userModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form action="#" method="POST" class="user" id="userRegisterForm">
                                @csrf
                                <div class="form-group">
                                    <input name="name" type="text"
                                        class="form-control form-control-user @error('name')is-invalid @enderror"
                                        id="exampleInputName" placeholder="Name">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input name="email" type="email"
                                        class="form-control form-control-user @error('email')is-invalid @enderror"
                                        id="exampleInputEmail" placeholder="Email Address">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <select name="userAccountType" id="userAccountType" class="form-control"
                                        style="border-radius: 20px">
                                        <option value="Admin">Admin</option>
                                        <option value="Amazon_User">Amazon user</option>
                                        <option value="Ebay_User">ebay user</option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input name="password" type="password"
                                            class="form-control form-control-user @error('password')is-invalid @enderror"
                                            id="exampleInputPassword" placeholder="Password">
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <input name="password_confirmation" type="password"
                                            class="form-control form-control-user @error('password_confirmation')is-invalid @enderror"
                                            id="exampleRepeatPassword" placeholder="Repeat Password">
                                        @error('password_confirmation')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">Create
                                    Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-stripe" id="table1">
            <thead>
                <tr>
                    <th>User name</th>
                    <th>Mail</th>
                    <th>User type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if (count($users) > 0)
                    @foreach ($users as $rs)
                        <tr>
                            <td>{{ $rs['name'] }}</td>
                            <td>{{ $rs['email'] }}</td>
                            <td>{{ $rs['userType'] }}</td>

                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item btnSetState" href="#">Change user type</a>
                                        <a class="dropdown-item btnDeleteUser" href="#"
                                            data-id="{{ $rs['id'] }}">Delete</a>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <script>
        $('#userRegisterForm').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting normally
            // Create a new FormData object
            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('register.save') }}",
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // show loading spinner or perform any other action
                    $(".loader-wrapper").show();
                },
                success: function(response) {
                    $(".loader-wrapper").hide();
                    console.log(response);
                    swal({
                        title: "Success",
                        text: "Saved successfully!",
                        icon: "success",
                        type: "success",
                        buttons: ["Close"],
                    });
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    var response = JSON.parse(xhr.responseText);
                    $(".loader-wrapper").hide();
                    swal({
                        title: "Alert",
                        text: response.message,
                        icon: "warning",
                        type: "warning",
                        buttons: ["Close"],
                    });
                }
            });
        });
        $('.btnDeleteUser').click(function() {
            var userId = $(this).data('id');
            $.ajax({
                url: "api/deleteUser",
                type: 'post',
                data: {
                    userId: userId
                },

                beforeSend: function() {
                    // show loading spinner or perform any other action
                    $(".loader-wrapper").show();
                },
                success: function(response) {
                    $(".loader-wrapper").hide();
                    console.log(response);
                    swal({
                        title: "Success",
                        text: "Deleted successfully!",
                        icon: "success",
                        type: "success",
                        buttons: ["Close"],
                    });
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    var response = JSON.parse(xhr.responseText);
                    $(".loader-wrapper").hide();
                    swal({
                        title: "Alert",
                        text: response.message,
                        icon: "warning",
                        type: "warning",
                        buttons: ["Close"],
                    });
                }
            });
        });
    </script>
@endsection
