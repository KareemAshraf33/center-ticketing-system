@extends('layouts.student_layout')

@section('title')
All || Tickets    
@endsection

@section('content')

<section class="ftco-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="table-wrap">
          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th>ID no.</th>
                <th>Owner Name</th>
                <th>Owner Phone</th>
                <th>Ticket Status</th>
                <th>Problem</th>
                <th>Support Comment</th>
              </tr>
            </thead>
            @foreach($tickets as $ticket)
            <tbody>
              <tr class="alert" role="alert">
                <th scope="row">{{$ticket->id}}</th>
                <td>{{$ticket->owner_name}}</td>
                <td>{{$ticket->owner_phone}}</td>
                <td>{{$ticket->status}}</td>
                <td>{{$ticket->problem}}</td>
                <td>{{$ticket->last_comment}}</td>
              </tr>
            </tbody>
            @endforeach
          </table>
        </div>
      </div>
    </div>
  </div>
</section>



<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
@endsection

