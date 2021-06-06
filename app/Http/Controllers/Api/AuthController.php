<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    public function login(Request $request)
    {

    }

    public function register(Request $request)
    {
         // return $request->all();

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        if($validator->fails())
         return response()->json([
            'message' => 'Validation error',
            'data' => $validator->errors()
         ],422);

         try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->name,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'message' => 'User Created successfully!',
                'status' => true,
                'user' => $user
             ],201);

         } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
         }
    }
}
