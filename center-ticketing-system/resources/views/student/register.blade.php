@extends('layouts.student_layout')

@section('title')
Register    
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

    <form action="{{url('/student/save')}}" method="post">
        @csrf
        <h1>Register</h1>
        <div class="input-box">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="Enter your name" required>
            <i class="bx bxs-user"></i>
        </div>
        <div class="input-box">
            <label class="form-label">Email address</label>
            <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="Enter your email" required>
            <i class="bx bxs-user"></i>
        </div>
        <div class="input-box">
            <label class="form-label">Phone</label>
            <input type="text" value="{{old('phone')}}" name="phone" class="form-control" placeholder="Enter your phone" required>
            <i class='bx bx-phone'></i>
        </div>
        <div class="input-box">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            <i class='bx bx-lock-alt'></i>
        </div>
        <button type="submit" class="btn">Register</button>

        <div class="login-link">
            <p>Already have an account?
                <a href="{{url('/student/login')}}">Login</a>
            </p>   
        </div>
    </form>
</div>
@endsection

