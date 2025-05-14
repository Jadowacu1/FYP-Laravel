<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\students;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class Authentication extends Controller {

    public function showForm() {
        return view( 'loginForm' );
    }

    public function showRegistrationFrom() {
        $departments = Department::all();

        return view( 'signup', compact( 'departments' ) );
    }

    public function studentRegistration( Request $request ) {
        // Validate form input
        $request->validate( [
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'StudentRegNumber' => [
                'required',
                'string',
                'unique:students,StudentRegNumber', // Check uniqueness in the students table
                'unique:users,reg_number', // Check uniqueness in the users table
            ],
            'Email' => 'required|email|unique:users,email|unique:students,Email',
            'PhoneNumber' => 'required|unique:students,PhoneNumber',
            'DepartmentCode' => 'required|string',
            'Gender' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'FirstName.required' => 'First name is required.',
            'LastName.required' => 'Last name is required.',
            'StudentRegNumber.required' => 'Student registration number is required.',
            'StudentRegNumber.unique' => 'The student registration number is already registered in our system.',
            'Email.required' => 'Email address is required.',
            'Email.unique' => 'This email address is already registered in our system.',
            'PhoneNumber.required' => 'Phone number is required.',
            'PhoneNumber.unique' => 'This phone number is already in use.',
            'DepartmentCode.required' => 'Department code is required.',
            'Gender.required' => 'Gender selection is required.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ] );

        // Insert into students table
        students::create( [
            'StudentRegNumber' => $request->StudentRegNumber,
            'FirstName' => $request->FirstName,
            'LastName' => $request->LastName,
            'Gender' => $request->Gender,
            'Email' => $request->Email,
            'PhoneNumber' => $request->PhoneNumber,
            'DepartmentCode' => $request->DepartmentCode,
        ] );

        $fullName = $request->FirstName . ' ' . $request->LastName;
        $user = new User();
        $user->name = $fullName;
        $user->reg_number = $request->StudentRegNumber;
        $user->email = $request->Email;
        $user->password = Hash::make( $request->password );
        $user->role = 'student';
        $user->save();

        return redirect()->back()->with( 'success', 'You have successfully signed up!' );

    }

    public function login( Request $request ) {
        $request->validate( [
            'password' => 'required|string',
        ] );

        // Attempt login by reg_number ( student )
        if ( $request->filled( 'reg_no' ) ) {
            $user = User::where( 'reg_number', $request->reg_no )->first();
        }
        // Attempt login by email ( staff: supervisor or department )
        else if ( $request->filled( 'email' ) ) {
            $user = User::where( 'email', $request->email )->first();
        } else {
            return back()->withErrors( [ 'login' => 'Invalid credentials.' ] );
        }

        if ( !$user || !Hash::check( $request->password, $user->password ) ) {
            return back()->withErrors( [ 'login' => 'Invalid credentials.' ] );
        }

        // Store user in session
        Auth::login( $user );

        // Store multiple values in the session
        Session::put( [
            'user_id' => $user->id,
            'reg_number' => $user->reg_number,  // use the same key as later
            'email' => $user->email,
            'role' => $user->role
        ] );

        // Redirect based on role from DB
        switch ( $user->role ) {
            case 'student':
            return redirect()->route( 'student.dashboard' );
            case 'supervisor':
            return redirect()->route( 'supervisor.dashboard' );
            case 'department':
            return redirect()->route( 'department.dashboard' );
            case 'hod':
            return redirect()->route( 'hod.dashboard' );

            default:
            Auth::logout();
            return redirect()->route( 'LoginForm' )->withErrors( [ 'login' => 'Unknown user role.' ] );
        }
    }

    public function logout(): RedirectResponse {
        Auth::logout();
        // Logs the user out
        session()->invalidate();
        // Invalidates the session
        session()->regenerateToken();
        // Regenerates the CSRF token

        return redirect( '/' )->with( 'success', 'You have been logged out.' );
    }

    public function redirect() {
        $user = Auth::user();
        switch ( $user->role ) {
            case 'student':
            return redirect()->route( 'student.dashboard' );
            case 'supervisor':
            return redirect()->route( 'supervisor.dashboard' );
            case 'department':
            return redirect()->route( 'department.dashboard' );
            case 'hod':
            return redirect()->route( 'hod.dashboard' );

            default:
            Auth::logout();
            return redirect()->route( 'LoginForm' )->withErrors( [ 'login' => 'Unknown user role.' ] );
        }
    }

    public function preferences() {
        return view( 'preferences' );
        // Create this Blade view
    }

    public function updatePassword( Request $request ) {
        $request->validate( [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ] );

        $user = auth()->user();

        if ( !Hash::check( $request->current_password, $user->password ) ) {
            return back()->withErrors( [ 'current_password' => 'Current password is incorrect' ] );
        }

        $user->password = Hash::make( $request->new_password );
        $user->save();

        return redirect()->route( 'dashboard.redirect' )->with( 'success', 'Password updated successfully!' );
    }
}