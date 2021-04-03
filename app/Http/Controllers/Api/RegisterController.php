<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class RegisterController extends Controller
{
    /**
     * Add a new user into the Database
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    function register(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'Name' => 'bail|required|string|min:2|max:200',
            'Mobile_number' => 'bail|required|string|min:10|max:15|unique:users,Mobile_number',
            'Email' => 'bail|required|string|email|max:220|unique:users,Email',
            'District' => 'bail|required|integer|min:1|exists:districts,id',
            'ULB_Name' => 'bail|required|integer|min:1|exists:ulb,id',
            'Secretariat_number' => 'bail|required|string|min:1|max:50',
            'username' => 'bail|required|string|min:2|max:200|unique:users,username',
            'password' => 'bail|required|string|min:8|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user = new User($request->only(
            'Name',
            'Mobile_number',
            'Email',
            'District',
            'ULB_Name',
            'Secretariat_number',
            'username',
        ));

        $user->password = Hash::make($request->input('password'));
        $user->Status = 1;

        if ($user->save()) {
            return response()->json(['message' => 'You have registered successfully!', 'data' => $user], 200);
        }
        return response()->json(['message' => 'Something went wrong!'], 400);
    }
}
