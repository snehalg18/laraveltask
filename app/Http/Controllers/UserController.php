<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Exception;

class UserController extends Controller
{

    public function __construct()
    {
        // Restrict access to only 'Admin' users
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'Admin') {
                Alert::error('Access Denied', 'You are not authorized to access this page.');
                return redirect('/');
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::Where('role','Employee')->orderBy('id_user', 'desc')->get();

        return view('user.user', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.user-add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'address' => 'required|max:50',
            'dob' => 'required',

        ]);
    
        $validated['password'] = bcrypt($validated['password']); // Hash password before saving
    
        $user = User::create($validated);
    
        Alert::success('Success', 'New Employee is created!');
        return redirect('/user');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_user)
    {
        $user = User::findOrFail($id_user);

        return view('user.user-edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_user)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $id_user . ',id_user',
            'dob' => 'required|date',
            'address' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);
    
        $user = User::findOrFail($id_user);
    
        // Only update the password if it's provided
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
    
        $user->update($validated);
    
        Alert::success('Success', 'Employee is updated!');
        return redirect('/user');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_user)
    {
        try {
            $deleteduser = User::findOrFail($id_user);

            $deleteduser->delete();

            Alert::success('Success', 'Employee is deleted !');
            return redirect('/user');
        } catch (Exception $ex) {
            Alert::warning('Error', 'Cant deleted, employee already used !');
            return redirect('/user');
        }
    }
}
