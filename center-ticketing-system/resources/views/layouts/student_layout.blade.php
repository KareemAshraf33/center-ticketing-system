<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{ asset('css/style2.css') }}">
</head>

<body>
    <nav class="hide-header">
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa fa-bars"></i>
        </label>
        <label class="logo">Center</label>
        <ul>
            @if (Auth::check())
                @if (Auth::user()->email == 'admin@gmail.com')
                <li><a href="{{url('/upload-excel/show')}}">Upload Excel</a></li>
                @endif
            <li><a class="active" href="{{url('/tickets/list')}}">Tickets</a></li>
            <li><a href="{{url('/tickets/create')}}">Create Ticket</a></li>
            <li><a class="link bg-yellow" target="_blank" href="https://api.whatsapp.com/send?phone=<?= +201090110191 ?>"><i class="fab fa fa-whatsapp"></i>&nbsp; Whatsapp Chatbot </a></li>
            <li><a href="{{url('/student/logout')}}">Logout</a></li>
            @else
            <li><a href="{{url('/student/login')}}">Login</a></li>
            <li><a href="{{url('/student/register')}}">Register</a></li>
            @endif
        </ul>
    </nav>

    @yield('content')

</body>

</html>    


