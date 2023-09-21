<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{

    public function register(){
        return view('student.register');
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name'=>'string|required|min:2',
            'email'=>'string|email|required|max:100|unique:users',
            'phone'=>'string|required|min:10',
            'password'=>'string|required|min:6'
        ]); 
       if($validator->fails())
       { 
           return redirect('/student/register') ->withErrors($validator) ->withInput();
       }
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $password = $request->password;
        
        $student = new Student();
        $student->name = $name;
        $student->email = $email;
        $student->phone = $phone;
        $student->password = Hash::make($password);
        $student->save();

        return redirect('/student/login');
    }

    public function login(){
        return view('student.login');
    }

    public function handlelogin(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'email'=>'string|required|email',
            'password'=>'string|required'
        ]); 
       if($validator->fails())
       { 
           return redirect('/student/login') ->withErrors($validator) ->withInput();
       }
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
    
        if (Auth::guard('students')->attempt($credentials)) {
            return redirect('/tickets/list');
        } else {
            return redirect()->back()->withErrors(['message' => 'Invalid email or password']);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/student/login');
    }
}
