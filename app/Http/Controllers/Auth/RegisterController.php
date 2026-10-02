<?php 
 
namespace App\Http\Controllers\Auth; 
 
use App\Http\Controllers\Controller; 
use App\Models\User; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
 
class RegisterController extends Controller 
{ 
    public function create() 
    { 
        return view('auth.register'); 
    } 
 
    public function store(Request $request) 
    { 
        $validated = $request->validate([ 
            'first_name' => ['required', 'string', 'max:100'], 
            'last_name' => ['required', 'string', 'max:100'], 
            'email' => ['required', 'email', 'max:255', 'unique:users,email'], 

            // Exactly 11 numbers
            'phone' => ['required', 'digits:11'], 

            // Must be at least 18 years old
            'date_of_birth' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            ], 

            'address' => ['nullable', 'string', 'max:1000'], 

            // Uppercase + lowercase + number + special character
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/',
            ], 
        ], [

            'phone.digits' =>
                'Phone number must contain exactly 11 digits.',

            'date_of_birth.required' =>
                'Date of birth is required.',

            'date_of_birth.before_or_equal' =>
                'You must be at least 18 years old to register.',

            'password.regex' =>
                'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',

        ]); 
 
        // Public registration can only create a client account. 
        $validated['role'] = 'client'; 
 
        $user = User::create($validated); 
 
        Auth::login($user); 
        $request->session()->regenerate(); 
 
        return redirect()->route('client.dashboard') 
            ->with('success', 'Welcome to M. Cares! Your account has been created.'); 
    } 
}