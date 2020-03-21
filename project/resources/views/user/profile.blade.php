@extends('layouts.layout')

@section('title')
Profile
@endsection

@section('links')
<link rel="stylesheet" type="text/css" href="/css/profile.css">
@endsection

@section('headerTitle')
Profile
@endsection

@section('content')
<div class="container mt-5">
    <div class="col">
        <div class="row">
            <div class="col mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="e-profile">
                            <div class="row">
                                <div class="col-12 col-sm-auto mb-3">
                                    <div class="mx-auto" style="width: 140px;">

                                        {{-- Photo --}}
                                        <div class="d-flex justify-content-center align-items-center rounded"
                                            style="height: 140px; background-color: rgb(233, 236, 239);">

                                            <img id="photoUrl" src="{{ Auth::user()->profile_img }}" alt=""
                                                style="height: 140px; width: 140px; object-fit: cover;">

                                        </div>
                                        {{-- End of Photo --}}

                                    </div>
                                </div>
                                <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                                    <div class="text-center text-sm-left mb-2 mb-sm-0">
                                        <h4 id="newName" class="pt-sm-2 pb-1 mb-0 text-nowrap">{{ Auth::user()->name }}
                                        </h4>
                                        <p class="mb-0">{{ Auth::user()->email }}</p>
                                        <div class="text-muted"><small>Last seen 2 hours ago</small></div>

                                        {{-- Change Photo --}}
                                        <div class="mt-2">

                                            <p>
                                                <a class="btn btn-primary" data-toggle="collapse"
                                                    href="#collapseExample" role="button" aria-expanded="false"
                                                    aria-controls="collapseExample">
                                                    <i class="fa fa-fw fa-camera"></i>Change Photo
                                                </a>
                                            </p>
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body">

                                                    <label for="inputPhotoUrl">Photo Url</label>

                                                    <input id="newPhotoUrl" type="text" class="form-control"
                                                        placeholder="Enter url">
                                                    <button onclick="updatePhoto()" type="submit"
                                                        class="btn btn-primary">Submit</button>

                                                </div>
                                            </div>
                                        </div>
                                        {{-- End of Change Photo --}}

                                    </div>
                                    <div class="text-center text-sm-right">
                                        <span class="badge badge-secondary">
                                            @if(Auth::user()->isAdmin == 1)
                                            administrator
                                            @else
                                            user
                                            @endif
                                        </span>
                                        <div class="text-muted"><small>Joined
                                                {{ Auth::user()->created_at->format('Y/m/d')}}</small></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content pt-3">
                                <div class="tab-pane active">
                                    <form class="form" novalidate="">
                                        <div class="row">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Full Name</label>
                                                            <input id="inputName" class="form-control" type="text"
                                                                name="name" value="{{ ucwords(Auth::user()->name )}}">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Username</label>
                                                            <input id="inputUsername" class="form-control" type="text"
                                                                name="username" placeholder="{{Auth::user()->username}}"
                                                                value="{{Auth::user()->username}}">
                                                        </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-6 mb-3">
                                                    <div class="mb-2"><b>Change Password</b></div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Current Password</label>
                                                                <input id="oldPassword" class="form-control"
                                                                    type="password" placeholder="••••••">
                                                            </div>
                                                            <div id="oldPasswordAlert"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>New Password</label>
                                                                <input id="newPassword" class="form-control"
                                                                    type="password" placeholder="••••••">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Confirm <span
                                                                        class="d-none d-xl-inline">Password</span></label>
                                                                <input id="confirmPassword" class="form-control"
                                                                    type="password" placeholder="••••••"></div>
                                                        </div>
                                                    </div>
                                                    <div id="alertMessage"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col d-flex justify-content-end">
                                                    <p>
                                                        <a class="a-btn btn btn-primary " type="submit"
                                                            onclick="updateInfo()" role="button">
                                                            Save Changes
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('scripts')
        <script>
            function updateInfo() {

                var name = $('#inputName').val();
                var username = $('#inputUsername').val();
                var password = $('#oldPassword').val();
                var inputNewPassword = $('#newPassword').val();
                var inputConfirmed = $('#confirmPassword').val();
                var errorOldPassword = "";


                if (inputNewPassword !== inputConfirmed) {
                    $('#alertMessage').html("Passwords do no match");
                    return false;
                }

                $.ajax({
                        url: "profile/updateInfo",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {
                            newName: name,
                            newUsername: username,
                            newPassword: inputNewPassword,
                            password: password,
                            errorOldPassword: ""
                        },
                        cache: false,
                        async: false,
                        dataType: "json",
                        processData: "false"
                    })

                    .done(function (response) {
                        $('#newName').html(response.name);
                        if (response.errorOldPassword == 1) {
                            $('#oldPasswordAlert').html("Old password doesn't match");
                        }
                    })
                    .fail(function (response) {

                    });
            }

            function updatePhoto() {

                var newPhotoUrl = $('#newPhotoUrl').val();

                $.ajax({
                        url: "profile/updatePhoto",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {
                            profile_img: newPhotoUrl
                        },
                        cache: false,
                        async: false,
                        dataType: "json",
                        processData: "false"
                    })

                    .done(function (response) {
                        $('#photoUrl').attr("src", response.newPhotoUrl);
                    })
                    .fail(function (response) {

                    });
            }

            // $(document).ready(function () {
            //     if (document.querySelector('.custom-control-input').checked == true) {
            //         disableGoogleAuth();
            //     } else {
            //         $('.modalBody').append('<h3>Google Auth Disabled</h3>');
            //     }
            // });

            var user = {!!json_encode($user->toArray(), JSON_HEX_TAG) !!};


            $(document).ready(function () {
                if (user.use2fa == 1) {
                    $('#customSwitch1').attr("checked", "checked");
                    // $('.disableModal').click(disableGoogleAuth());
                }else{
                    // $('.enableModal').click(enableGoogleAuth());
                }
            });

            $('.enableModal').click(function(){
                enableGoogleAuth();
            });

            $('.disableModal').click(function(){
                disableGoogleAuth();
            })

            

            function disableGoogleAuth() {
                $.ajax({
                        url: "profile/disableGoogleAuth",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {

                        },
                        cache: false,
                        async: false,
                        dataType: "json",
                        processData: "false"
                    })

                    .done(function (response) {
                        console.log(response);
                    })
                    .fail(function (response) {

                    });
            }

            function enableGoogleAuth() {
                $.ajax({
                        url: "profile/enableGoogleAuth",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {

                        },
                        cache: false,
                        async: false,
                        dataType: "json",
                        processData: "false"
                    })

                    .done(function (response) {
                        console.log(response);
                        if(response.use2fa == 1){
                            console.log("clicked enable")
                            console.log(response.qrSrc);
                          $('#secret').text(response.secret);
                          $('#qrImg').attr('src', response.qrSrc);
                        //   $('#enableModal').hide();
                        //   location.reload();
                        }

                    })
                    .fail(function (response) {

                    });
            }

            $("#close").click(function(){
              location.reload();
            })

        </script>

        @endsection
