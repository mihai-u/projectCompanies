
@extends('layouts.layout')

@section('title')
Admin Panel
@endsection

@section('links')
    <link rel="stylesheet" type="text/css" href="/css/list.css">
    <link rel="stylesheet" type="text/css" href="/css/adminPanel.css">
@endsection

@section('content')
    {{-- @if(Auth::user()->type == 'admin') --}}
        <div class="container d-flex justify-content-center align-items-center">
            <div class="row section-A">
                @foreach($users as $user)
                    <div class="col mb-3 target">
                        <div class="card card-outer" style="width: 20rem;">
                            
                                <h5>
                                    <span class="badge badge-danger">
                                        <a onclick="updateUsername({{ $user->id }})" class="abtn">Edit</a>
                                    </span>
                                    <span class="badge badge-dark"> Username </span>
                                    <span id="newUsername{{ $user->id }}"> {{ucwords($user->username)}} </span>
                                </h5>
                            
                            <div class="card-inner">
                                <div class="card-body">
                                    <div class="form-group" id="form">
                                        <input type="username" class="form-control" id="inputUsername{{ $user->id }}" aria-describedby="usernameHelp" placeholder="New username">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            
                                <h5>
                                    <span class="badge badge-danger">
                                        <a onclick="updateEmail({{ $user->id }})">Edit</a>
                                    </span>
                                    <span class="badge badge-dark"> Email </span>
                                    <span id="newEmail{{ $user->id }}"> {{$user->email}} </span>

                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </h5>
                            
                            <div class="card-inner" >
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="email" class="form-control" id="inputEmail{{ $user->id }}" aria-describedby="emailHelp" placeholder="New email">
                                    </div>
                                </div>
                            </div>

                            <hr>

                                <h5>
                                    <span class="badge badge-danger">
                                        <a onclick="updateAdmin({{ $user->id }})">Edit</a>
                                    </span>
                                    <span class="badge badge-dark"> Admin Status </span>
                                    <span id="newAdmin{{ $user->id }}">
                                        {{ucwords($user->type)}}
                                    </span>
                                </h5>
                            
                            <div class="card-inner">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Admin Privileges</label>
                                    <select class="form-control" id="inputAdmin{{ $user->id }}">
                                        <option value="1">Admin</option>
                                        <option value="0">Not Admin</option>
                                    </select>
                                </div>
                            </div>

                                <h5>
                                    <span class="badge badge-danger">
                                        <a onclick="updatePassword({{ $user->id }})" >Edit</a>
                                    </span>
                                    <span class="badge badge-dark"> Password </span>
                                    <span id="newPassword{{ $user->id }}"></span>
                                </h5>
                            
                        <div class="card-inner">
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="password" class="form-control" id="inputNewPassword{{ $user->id }}" placeholder="New password" style="margin-bottom: 10px;">
                                    <input type="password" class="form-control" id="confirmedNewPassword{{ $user->id }}" placeholder="Retype new password" style="margin-bottom: 10px;">
                                    <div class="registrationFormAlert" id="alertMessage{{ $user->id }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    {{-- @endif --}}

@endsection

@section('scripts')
<script>

function updateUsername(id){

    if(confirm("Are you sure?")){
        var username = $('#inputUsername' + id).val();

        $.ajax({
            url: "adminPanel/updateUsername",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {userid: id, name: username},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

        .done(function(response){
            $('#newUsername' + id).html(response.username);
        })
        .fail(function(response){

        });
    }else{
        return "aborted";
    }

}

function updateEmail(id){

    if(confirm("Are you sure?")){
        var email = $('#inputEmail' + id).val();

        $.ajax({
            url: "adminPanel/updateEmail",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {userid: id, userEmail: email},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

        .done(function(response){
            $('#newEmail' + id).html(response.userEmail);
        })
        .fail(function(response){

        });
    }else{
        return "aborted";
    }

}

function updateAdmin(id){

    if(confirm("Are you sure?")){
        var admin = $('#inputAdmin' + id).val();

        $.ajax({
            url: "adminPanel/updateAdmin",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {userid: id, adminValue: admin},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

    .done(function(response){

            if(response.adminValue == 1){
                $('#newAdmin' + id).html("Admin");
            }else{
                $('#newAdmin' + id).html("User");
            }
    })

    .fail(function(response){

    });
    }else{
        return "aborted";
    }
    
}

function updatePassword(id){

    if(confirm("Are you sure?")){
        var inputNewPassword = $('#inputNewPassword' + id).val();
        var inputConfirmed = $('#confirmedNewPassword' + id).val();

        if(inputNewPassword !== inputConfirmed){
            $('#alertMessage'+id).html("Passwords do no match");
            return false;
        }

        $.ajax({
            url: "adminPanel/updatePassword",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {userid: id, newPassword: inputNewPassword},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

        .done(function(response){

        })
        .fail(function(response){

        });
    }else{
        return "aborted";
    }
    
}


$(window).resize(function(){

    if ($(window).width() <= 768) {  
        $('.col').addClass("center-mobile").removeClass('col');
    }     

    if($(window).width() > 768){
        $('.center-mobile').addClass('col').removeClass('center-mobile');
    }

});

</script>

@endsection
