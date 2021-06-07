<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()) return send_error('Validation error',$validator->errors(),422);

        $credential = $request->only('email', 'password');

        if(Auth::attempt($credential))
        {
            $user = Auth::user();
            $data['name'] = $user->name;
            $data['access_token'] = $user->createToken('accessToken')->accessToken;

            return send_response('You are successfully logged in!', $data);
        }else{
            return send_error('Unauthorized','', 401);
        }

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
            return send_error('Validation error',$validator->errors(),422);

         try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // return response()->json([
            //     'message' => 'User Created successfully!',
            //     'status' => true,
            //     'user' => $user
            //  ],201);

             $data = [
                 'name' => $user->name,
                 'email' => $user->email
             ];

             return send_response('User Created successfully!',$data);

         } catch (\Exception $e) {
            return send_error($e->getMessage(),$e->getCode());
         }
    }
}
