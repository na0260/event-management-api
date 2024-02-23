<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Login $request)
    {
        $request->validated();
        $user = User::where('email', $request->email)->first();

        if (!$user){
            throw ValidationException::withMessages([
                'email'=>['Credentials incorrect']
            ]);
        }

        if (!Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'password'=>['Credentials incorrect']
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token'=>$token
        ]);
    }
}
