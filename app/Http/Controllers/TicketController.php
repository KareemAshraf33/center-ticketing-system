<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    //list of all tickets belongs to this student
    public function list()
    {
        $student = Auth::user();

        if($student->email == 'admin@gmail.com'){
            $tickets = Ticket::orderBy('id', 'DESC')->get();
            
            return view('student.tickets', [
                'tickets' => $tickets
            ]);
        }
        elseif ($student)
        {
            $tickets = Ticket::where('student_id', $student->id)->orderBy('id', 'DESC')->get();
            
            return view('student.tickets', [
                'tickets' => $tickets
            ]);
        }
    } 

    public function create()
    {
        return view('student.create');
    }
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), 
         ['owner_name' => 'required|max:100|min:3',
          'owner_phone' => 'required|max:100|min:3',
          'problem' => 'required|max:500|min:3',
          'image'=>'image|mimes:jpeg,png,jpg,gif,svg'
        ]); 
         if($validator->fails())
         { 
             return redirect('tickets/create') ->withErrors($validator) ->withInput();
         }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = Str::random(40) . '.' . $extension; // Generate a random 40-character string
            $destinationPath = public_path('storage');
            $image->move($destinationPath, $filename);
            $imageName = $filename;
        } else {
            $imageName = null;
        }

         $_name = $request->owner_name;
         $_phone = $request->owner_phone;
         $_problem = $request->problem;
         //insert into db
         $ticket = new Ticket();
         $ticket->student_id = Auth::user()->id;
         $ticket->owner_name = $_name;
         $ticket->owner_phone = $_phone;
         $ticket->problem = $_problem;
         $ticket->status = 'New';
         $ticket->image = $imageName;
         $ticket->save();
         
        return redirect('tickets/list');
    }
}
