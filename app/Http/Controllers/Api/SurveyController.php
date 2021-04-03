<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\Survey;
use App\Models\User;
use App\Repositories\CommonRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class SurveyController extends Controller
{
    /**
     * Add Survey
     *
     * @param Request $request
     * @return void
     */
    function addSurvey(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'Name' => 'bail|required|string|min:1|max:10|unique:survey,Name',
            'S/0_D/0_W/0' => 'bail|required|string|min:1|max:10',
            'Occupation' => 'bail|required|string|min:1|max:10',
            'Address' => 'bail|required|string|min:1:max:1000|unique:survey,Address',
            'Mobile_Number' => 'bail|required|string|min:7|max:15',
            'Anual_Income' => 'bail|required|numeric|min:0',
            'Intrested' => 'bail|required|integer|min:0|max:1',
            'Extend_Required' => 'bail|required|integer|min:1|exists:extent,id',
            'Prefered_Location' => 'bail|required|string|min:1|max:200',
            'Distance' => 'bail|required|integer|min:1|exists:distance,id',
            'otp' => 'bail|required|integer|min:111111|max:999999',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $otp = Otp::where('phone_number', $request->input('Mobile_Number'))
            ->where('otp', $request->input('otp'))
            ->where('user_id', auth()->id())
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'OTP entered is invalid!'], 400);
        }

        try {
            DB::beginTransaction();
            $survey = new Survey($request->only(
                'Name',
                'S/0_D/0_W/0',
                'Occupation',
                'Address',
                'Mobile_Number',
                'Anual_Income',
                'Intrested',
                'Extend_Required',
                'Prefered_Location',
                'Distance',
            ));

            $survey->Status = 1;
            $survey->ulb = auth()->user()->ULB_Name;
            $survey->survey_user = auth()->user()->Id;
            $survey->district = auth()->user()->District;
            $survey->save();
            $survey->refresh();

            $otp->delete();

            DB::commit();

            return response()->json(['message' => 'Survey has been added successfully!', 'data' => $survey], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong!'], 400);
        }
    }

    /**
     * Get the list og surveys
     *
     * @param Request $request
     * @return void
     */
    public function getSurvey(Request $request)
    {
        $surveys = Survey::where('survey_user', auth()->id())
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('Name', 'like', "%{$request->input('name')}%")
                        ->orWhere('Mobile_Number', 'like', "%{$request->input('name')}%");
                });
            })
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->where('Name', 'like', "%{$request->input('name')}%");
            })
            ->with([
                'ulb_name',
                'district_relation',
                'extent_relation',
                'distance_relation'
            ])
            ->orderBy('id', 'DESC')
            ->paginate(20);
        return response()->json(['message' => 'List of surveys!', 'data' => $surveys], 200);
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
            'Mobile_Number' => 'bail|required|string|min:7|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $otp = Otp::updateOrCreate([
            'type' => 1,
            'user_id' => auth()->id(),
            'phone_number' => $request->input('Mobile_Number'),
        ], [
            'expires_at' => now()->addMinute(15),
            'otp' => CommonRepository::genrateRandomNumber()
        ]);

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
            'Mobile_Number' => 'bail|required|string|min:7|max:15',
            'otp' => 'bail|required|integer|min:111111|max:999999',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $otp = Otp::where('phone_number', $request->input('Mobile_Number'))
            ->where('otp', $request->input('otp'))
            ->where('user_id', auth()->id())
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'OTP entered is invalid!'], 400);
        }

        return response()->json(['message' => 'OTP has been verified!', 'data' => $otp]);
    }
}
