<?php
  

  namespace App\Http\Controllers;

  use Illuminate\Http\Request;
  
  class AdminController extends Controller
  {
      public function showLoginForm()
      {
          return view('admin.login');
      }
  
      public function login(Request $request)
      {
          $request->validate([
              'password' => 'required'
          ]);
  
          if ($request->password === config('admin.password')) {
              // Set session variables
              $request->session()->put('admin_logged_in', true);
              $request->session()->put('admin_login_time', now());
              
              return redirect()->route('registercompany');
          }
  
          return back()->withErrors(['password' => 'Incorrect password']);
      }
  
      public function logout(Request $request)
      {
          $request->session()->forget('admin_logged_in');
          $request->session()->forget('admin_login_time');
          return redirect()->route('admin.login');
      }
  }
  