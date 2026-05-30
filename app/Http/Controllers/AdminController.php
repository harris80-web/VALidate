<?php

namespace App\Http\Controllers;

use App\Enums\QuestionCategoryType;
use App\Enums\RespondentType;
use App\Http\Requests\AdminEditRequest;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Requests\DeleteAdminRequest;
use App\Models\Admin;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\Respondent;
use App\Models\Response;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RespondentsExport;
use App\Models\Region;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalResponses = Respondent::totalCount();
        $totalSuggestions = Respondent::totalSuggestions();

        // Suggestions
        $suggestionsLabels = ['With Suggestions', 'No Suggestion'];
        $suggestionsData = [
            Respondent::whereNotNull('suggestion')->count(),
            Respondent::whereNull('suggestion')->count(),
        ];

        // --- Net CC Awareness ---
        $totalRespondents = Respondent::count();

        $ccQuestionIds = Question::whereHas('category', function ($q) {
            $q->where('type', QuestionCategoryType::CITIZEN_CHARTER);
        })->pluck('id')->all();

        $options = DB::table('question_options')
            ->whereIn('question_id', $ccQuestionIds)
            ->orderBy('question_id')
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('question_id');

        $optionsByQuestion = [];
        $maxPosByQuestion = [];
        foreach ($options as $qid => $group) {
            $ids = $group->pluck('id')->values()->all();
            $optionsByQuestion[$qid] = $ids;
            $maxPosByQuestion[$qid] = count($ids);
        }

        $responsesByRespondent = DB::table('responses')
            ->whereIn('question_id', $ccQuestionIds)
            ->select('respondent_id', 'question_id', 'question_option_id')
            ->get()
            ->groupBy('respondent_id');

        $awareRespondentIds = [];
        foreach ($responsesByRespondent as $respondentId => $respGroup) {
            foreach ($respGroup as $r) {
                $qid = $r->question_id;
                if (!isset($optionsByQuestion[$qid])) continue;
                $pos = array_search($r->question_option_id, $optionsByQuestion[$qid]);
                if ($pos !== false && ($pos + 1) === $maxPosByQuestion[$qid]) {
                    $awareRespondentIds[] = $respondentId;
                    break;
                }
            }
        }

        $awareRespondentCount = count(array_unique($awareRespondentIds));

        $netCcAwareness = $totalRespondents
            ? round(($awareRespondentCount / $totalRespondents) * 100, 2)
            : 0;

        // Chart labels/data
        $ccLabels = ['Aware', 'Not Aware'];
        $ccData = [
            (int) $awareRespondentCount,
            max(0, (int)$totalRespondents - (int)$awareRespondentCount)
        ];

        // --- Overall SQD Average ---
        $sqdResponses = DB::table('responses')
            ->select('responses.question_id', 'responses.question_option_id', 'question_options.description')
            ->join('questions', 'responses.question_id', '=', 'questions.id')
            ->join('question_categories', 'questions.category_id', '=', 'question_categories.id')
            ->join('question_options', 'responses.question_option_id', '=', 'question_options.id')
            ->where('question_categories.type', QuestionCategoryType::SERVICE_QUALITY_DIMENSION)
            ->get();

        $scores = [];
        if ($sqdResponses->isNotEmpty()) {
            $questionIds = $sqdResponses->pluck('question_id')->unique()->values()->all();
            $options = DB::table('question_options')
                ->whereIn('question_id', $questionIds)
                ->orderBy('id')
                ->get()
                ->groupBy('question_id');

            foreach ($sqdResponses as $r) {
                $desc = $r->description ?? '';
                if (preg_match('/^\s*(\d+(?:\.\d+)?)/', $desc, $m)) {
                    $scores[] = (float) $m[1];
                    continue;
                }

                $optsForQ = $options[$r->question_id] ?? null;
                if ($optsForQ) {
                    $ids = $optsForQ->pluck('id')->values()->all();
                    $pos = array_search($r->question_option_id, $ids);
                    if ($pos !== false) $scores[] = $pos + 1;
                }
            }
        }

        $overallSqdAverage = count($scores)
            ? round(array_sum($scores) / count($scores), 2)
            : 0;

        // SQD Per Question
        $sqdQuestions = Question::getAllQuestionsByCategoryType(QuestionCategoryType::SERVICE_QUALITY_DIMENSION);
        $sqLabels = [];
        $sqData = [];
        foreach ($sqdQuestions as $q) {
            $sqLabels[] = $q->name;
            $respScores = [];
            $opts = $q->options()->orderBy('id')->get()->pluck('id')->values()->all();
            $responses = $q->responses()->with('option')->get();

            foreach ($responses as $r) {
                $desc = $r->option->description ?? '';
                if (preg_match('/^\s*(\d+(?:\.\d+)?)/', $desc, $m)) {
                    $respScores[] = (float) $m[1];
                    continue;
                }
                $pos = array_search($r->question_option_id, $opts);
                if ($pos !== false) $respScores[] = $pos + 1;
            }
            $sqData[] = count($respScores)
                ? round(array_sum($respScores) / count($respScores), 2)
                : 0;
        }

        // RETURN VIEW WITH ALL NEEDED VARIABLES
        return view(
            "pages.admin.dashboard",
            compact(
                'totalResponses',
                'totalSuggestions',
                'suggestionsLabels',
                'suggestionsData',
                'netCcAwareness',
                'overallSqdAverage',
                'ccLabels',
                'ccData',
                'sqLabels',
                'sqData'
            )
        );
    }

    public function profile()
    {
        return view("pages.admin.profile");
    }

    public function surveyContent()
    {
        $categories = QuestionCategory::with(['questions.options'])->get();

        return view("pages.admin.survey-content", compact('categories'));
    }

    public function userManagement()
    {
        $admins = Admin::where('id', '!=', Auth::id())->get();

        return view("pages.admin.user-management", compact('admins'));
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
        //
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
    public function profileUpdate(AdminEditRequest $request)
    {
        $requestData = $request->validated();

        try {
            DB::beginTransaction();

            $admin = Auth::user();
            $admin->email = $requestData['email'];
            $admin->name = $requestData['name'];

            if (!empty($requestData['password'])) {
                $admin->password = Hash::make($requestData['password']);
            }

            $admin->status = $requestData['status'];
            $admin->role = $requestData['role'];

            $admin->save();

            DB::commit();

            return redirect()->route('admin.profile')
                ->with('success', 'Profile updated successfully.');
        } catch (Exception $e) {

            DB::rollBack();
            return redirect()->route('admin.profile')
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    // Admin CRUD: create admin
    public function storeAdmin(\App\Http\Requests\StoreAdminRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();
            $admin = new \App\Models\Admin();
            $admin->name = $data['name'];
            $admin->email = $data['email'];
            $admin->password = Hash::make($data['password']);
            $admin->role = $data['role'] ?? 2;
            $admin->status = $data['status'] ?? 1;
            $admin->save();
            DB::commit();
            return response()->json(['admin' => $admin], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create admin'], 500);
        }
    }

    // Admin CRUD: update
    public function updateAdmin(\App\Http\Requests\UpdateAdminRequest $request, $id)
    {
        $admin = \App\Models\Admin::find($id);
        if (!$admin) return response()->json(['message' => 'Admin not found'], 404);

        $data = $request->validated();

        try {
            DB::beginTransaction();
            $admin->name = $data['name'];
            $admin->email = $data['email'];
            if (!empty($data['password'])) $admin->password = Hash::make($data['password']);
            if (isset($data['role'])) $admin->role = $data['role'];
            if (isset($data['status'])) $admin->status = $data['status'];
            $admin->save();
            DB::commit();
            return response()->json(['admin' => $admin], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update admin'], 500);
        }
    }

    // Admin CRUD: destroy
    public function destroyAdmin(\App\Http\Requests\DeleteAdminRequest $request, $id)
    {
        $admin = \App\Models\Admin::find($id);
        if (!$admin) return response()->json(['message' => 'Admin not found'], 404);

        // Prevent deleting currently authenticated admin
        if (Auth::id() == $admin->id) return response()->json(['message' => 'Cannot delete currently logged-in admin'], 400);

        try {
            DB::beginTransaction();
            $admin->delete();
            DB::commit();
            return response()->json(['message' => 'Admin deleted'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete admin'], 500);
        }
    }

    // return admin JSON
    public function showAdmin($id)
    {
        $admin = \App\Models\Admin::find($id);
        if (!$admin) return response()->json(['message' => 'Not found'], 404);
        return response()->json(['admin' => $admin], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function login()
    {
        return view("pages.admin.login");
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('admin.dashboard'))
                ->withSuccess('You have Successfully loggedin');
        }

        // If login fails, redirect back with error
        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function report(Request $request)
    {
        $regions = Region::getAllRegion();

        // Start query
        $query = Respondent::query();

        if ($request->filled('client_type')) {
            $query->where('type', $request->client_type);
        }

        if ($request->filled('region')) {
            $query->where('region_id', $request->region);
        }

        if ($request->filled('rg_date')) {
            $query->whereDate('created_at', $request->rg_date);
        }

        // Get counts grouped by region_id
        $respondentsCountByRegion = $query
            ->select('region_id', DB::raw('count(*) as total'))
            ->groupBy('region_id')
            ->with('region') // eager load region
            ->get();

        return view('pages.admin.report', compact('regions', 'respondentsCountByRegion'));
    }



    public function generateReport(Request $request)
    {
        $headers = ['ID', 'Name', 'Age', 'Email'];

        // Filters
        $clientType = $request->client_type;
        $region = $request->region;
        $date = $request->date;

        $query = Respondent::with("region");

        if ($clientType) $query->where('type', $clientType);
        if ($region) $query->where('region_id', $region);
        if ($date) $query->whereDate('created_at', $date);

        $respondents = $query->get();

        // FORMAT REQUESTED
        $format = $request->format;

        if (!$format) {
            return back()->with('error', 'Please select a file format.');
        }

        // CSV EXPORT
        if ($format === 'CSV') {
            return Excel::download(new RespondentsExport($respondents, $headers), 'report.csv');
        }

        // EXCEL EXPORT
        if ($format === 'EXCEL') {
            return Excel::download(new RespondentsExport($respondents, $headers), 'report.xlsx');
        }

        // PDF EXPORT
        if ($format === 'PDF') {
            $pdf = PDF::loadView('reports.pdf', compact('respondents'));
            return $pdf->download('report.pdf');
        }

        // ARTA (HTML)
        if ($format === 'ARTA') {
            return view('reports.pdf', compact('respondents'));
        }

        return back()->with('error', 'Invalid format selected.');
    }
}
