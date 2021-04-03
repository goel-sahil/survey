<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendTextMessageJob;
use App\Models\Otp;
use App\Models\User;
use App\Repositories\CommonRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'Mobile_number' => 'bail|required|string|min:10|max:10|unique:users,Mobile_number',
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

        $otp = Otp::where('phone_number', $request->input('Mobile_number'))
            ->where('otp', $request->input('otp'))
            ->where('type', 2)
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'The entered OTP is invalid!'], 400);
        }

        try {
            DB::beginTransaction();
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
            $user->save();
            $otp->delete();
            DB::commit();
            return response()->json(['message' => 'Successfully registered!', 'data' => $user], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong!'], 400);
        }
    }

    /**
     * Send the OTP to Mobile number
     *
     * @param Request $request
     * @return void
     */
    public function createOTP(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'Mobile_number' => 'bail|required|string|min:10|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $otp = Otp::updateOrCreate([
            'type' => 2,
            'phone_number' => $request->input('Mobile_number'),
        ], [
            'expires_at' => now()->addMinute(15),
            'otp' => CommonRepository::genrateRandomNumber()
        ]);

        // Send message
        $message = "Please verify your phone number using the OTP: {$otp->otp}.";
        SendTextMessageJob::dispatch($request->input('Mobile_number'), $message);
        return response()->json(['message' => 'OTP has been sent successfully!', 'data' => $otp]);
    }

    /**
     * Verify the OTP
     *
     * @param Request $request
     * @return void
     */
    public function verifyOTP(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'Mobile_number' => 'bail|required|string|min:10|max:10',
            'otp' => 'bail|required|integer|min:111111|max:999999',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $otp = Otp::where('phone_number', $request->input('Mobile_number'))
            ->where('otp', $request->input('otp'))
            ->where('type', 2)
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'The entered OTP is invalid!'], 400);
        }

        return response()->json(['message' => 'OTP has been verified!', 'data' => $otp]);
    }
}
