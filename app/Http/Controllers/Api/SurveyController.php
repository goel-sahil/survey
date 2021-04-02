<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\Request;

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
            'Name' => 'bail|required|string|min:1|max:10',
            'S/0_D/0_W/0' => 'bail|required|string|min:1|max:10',
            'Occupation' => 'bail|required|string|min:1|max:10',
            'Address' => 'bail|required|string|min:1:max:1000',
            'Mobile_number' => 'bail|required|string|min:7|max:15',
            'Anual_Income' => 'bail|required|numeric|min:0',
            'Intrested' => 'bail|required|integer|min:0|max:1',
            'Extend_Required' => 'bail|required|string|min:1|max:20',
            'Prefered_Location' => 'bail|required|string|min:1|max:200',
            'Distance' => 'bail|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

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
        // $survey->Date_Submission = now();
        $survey->ulb = auth()->user()->ULB_Name;
        $survey->survey_user = auth()->id();

        if ($survey->save()) {
            return response()->json(['message' => 'Survey has been added successfully!', 'data' => $survey], 200);
        }
        return response()->json(['message' => 'Something went wrong!'], 400);
    }
}
