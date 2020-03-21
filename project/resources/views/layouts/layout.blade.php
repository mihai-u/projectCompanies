<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://kit.fontawesome.com/775e8e49e9.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/css/style.css">

    @yield('links')

</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a href="/dashboard" class="navbar-brand">Project Companies</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
            Hello, {{Auth::user()->name}} <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav mr-auto ml-auto">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">Dashboard</a>
                </li>
                @if(Auth::user()->type == 'admin')
                    <li class="nav-item">
                        <a href="/admin/adminPanel" class="nav-link">Admin Panel</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="/companies" class="nav-link">Companies</a>
                </li>
                <li class="nav-item">
                    <a href="/tasks" class="nav-link">Tasks</a>
                </li>
                <li class="nav-item">
                    <a href="/reports" class="nav-link">Reports</a>
                </li>
                <li class="nav-item d-lg-none d-xl-none" id="hide">
                    <a href="/user/profile" class="nav-link">Profile</a>
                </li>
                <li class="nav-item hide d-lg-none d-xl-none" id="hide">
                    <a href="/logout" class="nav-link">Logout</a>
                </li>
            </ul>

            <ul class="navbar-nav d-none d-lg-block">
                <li class="nav-item">
                    <a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Hello, {{ucfirst(Auth::user()->username)}} <i class="fas fa-user-circle"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a href="user/profile" class="dropdown-item">Profile</a>
                        <a href="/logout" class="dropdown-item">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
	    
        @yield('content')
      
            

        <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="/js/script.js"></script>
    <!-- <script src="/js/app.js"> </script> -->
    @yield('scripts')
</body>
</html>
