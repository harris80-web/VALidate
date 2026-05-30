<?php

namespace App\Http\Controllers;

use App\Enums\QuestionCategoryType;
use App\Http\Requests\CitizenCharterRequest;
use App\Http\Requests\ServiceQualityDimensionRequest;
use App\Http\Requests\SuggestionRequest;
use App\Http\Requests\SurveyStartRequest;
use App\Models\Question;
use App\Models\Region;
use App\Models\Respondent;
use App\Models\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        if (session()->has('form')) {
            session()->forget('form');
        }
        return view("pages.user.index");
    }

    public function surveyConsent()
    {
        return view("pages.user.survey-consent");
    }

    public function surveyStart()
    {
        $regions = Region::getAllRegion();
         return view("pages.user.survey-start", compact('regions'));
    }

    public function surveyStartSubmit(SurveyStartRequest $request)
    {
        // Get validated data from the Form Request
        $validated = $request->validated();

        // Save step 1 data to session
        session(['form.stepper.survey_start' => $validated]);

        // Proceed to Step 2
        return redirect()->route('user.citizens-charter');
    }


    public function citizensCharter()
    {
        $questions = Question::getAllQuestionsByCategoryType(QuestionCategoryType::CITIZEN_CHARTER);

        return view("pages.user.citizens-charter", compact('questions'));
    }

    public function citizensCharterSubmit(CitizenCharterRequest $request)
    {
        $validated = $request->validated();

        // Save the answers in session under a structured key
        session()->put('form.stepper.citizens_charter_questions', $validated);

        // Redirect to the next step
        return redirect()->route('user.service-quality');
    }

    public function serviceQuality()
    {
        $questions = Question::getAllQuestionsByCategoryType(QuestionCategoryType::SERVICE_QUALITY_DIMENSION);

        return view("pages.user.service-quality" , compact('questions'));
    }

    
    public function serviceQualityDimensionSubmit(ServiceQualityDimensionRequest $request)
    {
        $validated = $request->validated();

        // Save the answers in session under a structured key
        session()->put('form.stepper.service_quality_dimension_questions', $validated);

        // Redirect to the next step
        return redirect()->route('user.suggestion');
    }

    public function suggestion()
    {
        return view("pages.user.suggestion");
    }

    public function suggestionSubmit(SuggestionRequest $request)
    {
        // Get the current form data from the session
        $form = session('form', []);

        // Ensure the 'stepper.survey_start' array exists
        if (blank($form['stepper']['survey_start'])) {
            $form['stepper']['survey_start'] = [];
        }

        // Append the email and suggestion from the request
        $form['stepper']['survey_start']['email'] = $request->input('email');
        $form['stepper']['survey_start']['suggestion'] = $request->input('suggestion');

        // Save back to session
        session(['form' => $form]);

        return response()->json([
            'message' => 'Validation passed',
        ], 200);
    }

    public function finished()
    {
         return view("pages.user.finished");
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $form = session('form');

        try {
            if (blank($form)) {
                throw ValidationException::withMessages(['form' => 'No form data found in session.']);
            }

            $respondentData = $form['stepper']['survey_start'] ?? null;
            if (blank($respondentData)) {
                throw ValidationException::withMessages(['form' => 'No survey start data found in session.']);
            }

            $citizenCharterData = $form['stepper']['citizens_charter_questions'] ?? null;
            if (blank($citizenCharterData)) {
                throw ValidationException::withMessages(['form' => 'No Citizen Charter data found in session.']);
            }

            $serviceQualityDimensionData = $form['stepper']['service_quality_dimension_questions'] ?? null;
            if (blank($serviceQualityDimensionData)) {
                throw ValidationException::withMessages(['form' => 'No Service Quality data found in session.']);
            }

            DB::transaction(function () use ($respondentData, $citizenCharterData, $serviceQualityDimensionData) {

                // Create main respondent
                $respondent = Respondent::create([
                    'type' => $respondentData['type'],
                    'age' => $respondentData['age'],
                    'gender' => $respondentData['gender'],
                    'region_id' => $respondentData['region'],
                    'service' => $respondentData['service'],
                    'submitted_date' => $respondentData['submitted_date'],
                    'suggestion' => $respondentData['suggestion'] ?? null,
                    'email' => $respondentData['email'] ?? null,
                ]);

                // Create Citizen Charter responses
                foreach ($citizenCharterData as $questionId => $optionId) {
                    Response::create([
                        'respondent_id' => $respondent->id,
                        'question_id' => str_replace('question_', '', $questionId),
                        'question_option_id' => $optionId,
                    ]);
                }

                // Create Service Quality responses
                foreach ($serviceQualityDimensionData as $questionId => $optionId) {
                    Response::create([
                        'respondent_id' => $respondent->id,
                        'question_id' => str_replace('question_', '', $questionId),
                        'question_option_id' => $optionId,
                    ]);
                }
            });

            // Clear session after successful store
            session()->forget('form');

            return response()->json([
                'message' => 'Response Created',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
