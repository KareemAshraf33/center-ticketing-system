@extends('layouts.student_layout')

@section('title')
Upload Excel   
@endsection

@section('content')

<div class="wrapper">

    {{-- validation errors --}}
    @if($errors->any())
    @foreach($errors->all() as $error)
    <div class="alert alert-danger">{{$error}}</div>
    @endforeach
    @endif

    <form action="{{ url('/upload-excel') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h1>Upload Excel</h1>
        <div class="input-box">
            <label class="form-label">Upload Excel</label>
            <input type="file" class="input-image" name="excel_file">
        </div>
        <button type="submit" class="btn">Upload</button>
    </form>
    
</div>

@endsection