@extends('layouts.student_layout')

@section('title')
Login    
@endsection

@section('content')
<style>
    .hide-header{
        display: none;
    }
</style>

  <div class="wrapper">

    @if($errors->any())
    @foreach($errors->all() as $error)
    <div class="alert alert-danger">{{$error}}</div>
    @endforeach
    @endif

    <form action="{{url('/student/handlelogin')}}" method="POST">
        @csrf
        <h1>Login</h1>
        <div class="input-box">
            <label class="form-label">Email address</label>
            <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="Enter your email" required>
            <i class="bx bxs-user"></i>
        </div>
        <div class="input-box">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            <i class='bx bx-lock-alt'></i>
        </div>
        <button type="submit" class="btn">Login</button>

        <div class="login-link">
            <p>Don't have an account?
                <a href="{{url('/student/register')}}">Register</a>
            </p>
            
        </div>
    </form>
</div>
@endsection

