@extends('layouts.student_layout')
@section('title')
Create Ticket    
@endsection

@section('content')

<div class="wrapper">

    {{-- validation errors --}}
    @if($errors->any())
    @foreach($errors->all() as $error)
    <div class="alert alert-danger">{{$error}}</div>
    @endforeach
    @endif
    
    <form action="{{url('tickets/store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <h1>Create Ticket</h1>
        <div class="input-box">
            <label class="form-label">Contact Name</label>
            <input type="text" value="{{old('owner_name')}}" name="owner_name" class="form-control" placeholder="Enter your name" required>
            <i class="bx bxs-user"></i>
        </div>
        <div class="input-box">
            <label class="form-label">Contact Phone</label>
            <input type="text" value="{{old('owner_phone')}}" name="owner_phone" class="form-control" placeholder="Enter your phone" required>
            <i class='bx bx-phone'></i>
        </div>
            <div class="input-box">
            <label class="form-label">Problem</label>
            <input type="text" value="{{old('problem')}}" name="problem" class="form-control" placeholder="Enter your problem" required>
        </div>
        <div class="input-box">
            <label class="form-label">Image</label>
            <input class="input-image" type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn">Create Ticket</button>
    </form>
</div>

@endsection