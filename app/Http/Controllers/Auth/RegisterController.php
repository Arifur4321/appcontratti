<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Import DB facade
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    // Update the validator to make the avatar optional
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'dob' => ['required', 'date', 'before:today'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'], // Nullable for optional avatar
        ]);
    }

    // Handle user creation
    protected function create(array $data)
    {
        // Handle avatar upload only if a file is provided
        if (request()->hasFile('avatar')) {
            $avatar = request()->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
        } else {
            $avatarName = 'default.png'; // Default avatar if none is uploaded
        }

        // Fetch the last row id from the companies table
        $lastCompany = DB::table('companies')->latest('id')->first();
        $companyId = $lastCompany ? $lastCompany->id : null;

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'dob' => date('Y-m-d', strtotime($data['dob'])),
            'avatar' => "/images/" . $avatarName, // Store the avatar or default image path
            'company_id' => $companyId,
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
    
        $user = $this->create($request->all());
    
        // Fire the registered event (which sends the verification email)
        event(new Registered($user));
    
        // Log out the user
        $this->guard()->logout();
    
        // Set a flash message for SweetAlert
        session()->flash('registration_success', 'Your registration is successful. Please verify your email.');
    
        // Return a view that will show SweetAlert
        return view('auth.registration_success');
    }
    


}
