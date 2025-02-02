<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function index()
    {

        return view('auth.login', [
            'title' => 'Login',
        ]);
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            // Check if the authenticated user is an Admin
            if (auth()->user()->role === 'Admin') {
                return redirect('/dashboard');
            } else {
                // Redirect to a different page for non-admins
                return redirect('/emp_dashboard');
            }
    
            Alert::success('Success', 'Login successful!');
        } else {
            Alert::error('Error', 'Login failed!');
            return redirect('/login');
        }
    }
    
    public function register()
    {
        return view('auth.register', [
            'title' => 'Register',
        ]);
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'passwordConfirm' => 'required|same:password',
            'address' => 'required|max:50',
            'dob' => 'required',

        ]);

        $validated['password'] = Hash::make($request['password']);

        $user = User::create($validated);

        Alert::success('Success', 'Registered successfully !');
        return redirect('/login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
        Alert::success('Success', 'Log out successfully !');
        return redirect('/login');
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        // Check if the user exists in your database
        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            // Log the user in
            auth()->login($existingUser, true);
            return redirect()->to('/emp_dashboard');  // Redirect to your desired page
        } else {
            // Create a new user in the database if it doesn't exist
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'google_id' => $user->getId(),
            ]);

            auth()->login($newUser, true);
            return redirect()->to('/emp_dashboard');
        }
    }


  
}
