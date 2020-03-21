@extends('layouts.layout')

@section('title')
Add Task
@endsection

@section('links')
    <!-- <link rel="stylesheet" type="text/css" href="/css/dashboard.css"> -->
    <link rel="stylesheet" type="text/css" href="/css/tasks.css">
@endsection

@section('content')
    <div class="container">
        <h1 class="md-3">Add Task</h1>
        <form>
            <div class="form-group">
                <input type="text" class="form-control" id="inputTitle" placeholder="Title">
                <div class="invalid-feedback text-left">
                    Please enter a title
                </div>
            </div>
            <div class="form-group">
                <textarea class="form-control" id="inputDescription" rows="3" placeholder="Description"></textarea>
                <div class="invalid-feedback text-left">
                    Please enter a description
                </div>
            </div>
            <div class="form-group">
                <label for="inputCompany">Company</label>
                <select class="form-control dynamic" id="inputCompany">
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                    <option value="{{ $company->id }}" class="company{{ $company->id }}">{{ $company->companyName }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback text-left">
                    Please select a company
                </div>
            </div>
            <div class="form-group">
                <label for="inputProject">Project</label>
                <select name="state" class="form-control" id="inputProject">
                    <option value="">Select Project</option>
                </select>
                <div class="invalid-feedback text-left">
                    Please select a project
                </div>
            </div>
            <div class="form-group">
                <label for="inputResponsible">Responsible</label>
                <select class="form-control" id="inputResponsible">
                    @foreach($users as $user)
                        @if($user->type == 'user')
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
                <div class="invalid-feedback text-left">
                    Please select a responsible
                </div>
            </div>
            <a class="btn btn-primary" role="button" href="#" onclick="submitInfo()">Submit</a>
          </form>
    </div>
@endsection

@section('scripts')
    <script>

        $(document).ready(function(){

        $('.dynamic').change(function () {

            if ($(this).val() != '') {

                var company_id = $('#inputCompany').children("option:selected").val();

                $.ajax({
                    url: "add/fetch",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    data: { id: company_id },
                    cache: false,
                    async: false,
                    dataType: "json",
                    processData: "false"
                })

                .done(function (response) {
                    $.each(response, function (i) {
                        $('#inputProject').empty();

                        for(i=0; i<response.project.length; i++){

                            $('#inputProject').append("<option value='"+response.project[i].id+"'>"+response.project[i].title+"</option>");

                        }

                    });
                });
            };


        })
        });

    $('#company').change(function(){
        $('project').val('');
    });

    function submitInfo(){

        var title = $('#inputTitle').val();
        var description = $('#inputDescription').val();
        var company = $('#inputCompany').val();
        var project = $('#inputProject').val();
        var responsible = $('#inputResponsible').val();

        $.ajax({
            url: "add",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {title: title, description: description, company: company, project: project, responsible: responsible},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

        .done(function(response){
            if(response.errorMessage == ""){
                window.location.href = "/tasks";
            }else if(response.errorMessage == "titleEmpty"){
                $('#inputTitle').addClass('is-invalid');
            }else if(response.errorMessage == "descriptionEmpty"){
                $('#inputDescription').addClass('is-invalid');
            }else if(response.errorMessage == "projectEmpty"){
                $('#inputProject').addClass('is-invalid');
            }else if(response.errorMessage == "responsibleEmpty"){
                $('#inputResponsible').addClass('is-invalid');
            }else if(response.errorMessage == "companyEmpty"){
                $('#inputCompany').addClass('is-invalid');
            }
        })
        .fail(function(response){

        });
        }

    </script>
@endsection
