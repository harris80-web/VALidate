<?php

namespace App\Http\Controllers;

use App\Enums\QuestionType;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreQuestionRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $question = new Question();
            $question->category_id = $data['category_id'];
            $question->name = $data['name'];
            // default type (e.g. single-choice) if not provided
            $question->type = $data['type'] ?? QuestionType::MULTIPLE_CHOICES;
            $question->save();

            if (!empty($data['options']) && is_array($data['options'])) {
                foreach ($data['options'] as $opt) {
                    $qo = new QuestionOption();
                    $qo->question_id = $question->id;
                    $qo->description = $opt;
                    $qo->save();
                }
            }

            DB::commit();

            return response()->json(['question' => $question->load('options')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create question.'], 500);
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
    public function update(UpdateQuestionRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $question = Question::findOrFail($id);
            $question->name = $data['name'];
            $question->save();

            // Handle deleted options
            if (!empty($data['deleted_option_ids']) && is_array($data['deleted_option_ids'])) {
                // soft delete options
                QuestionOption::whereIn('id', $data['deleted_option_ids'])->delete();
            }

            // Handle create/update of options
            if (!empty($data['options']) && is_array($data['options'])) {
                foreach ($data['options'] as $opt) {
                    if (!empty($opt['id'])) {
                        // update existing
                        $qo = QuestionOption::find($opt['id']);
                        if ($qo) { $qo->description = $opt['description']; $qo->save(); }
                    } else {
                        // new option
                        $qo = new QuestionOption();
                        $qo->question_id = $question->id;
                        $qo->description = $opt['description'];
                        $qo->save();
                    }
                }
            }

            DB::commit();

            return response()->json(['question' => $question->load('options')], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Question not found.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update question.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $question = Question::findOrFail($id);

            DB::beginTransaction();

            // delete related options via relationship (single query)
            $question->options()->delete();

            $question->delete();

            DB::commit();

            return response()->json(['message' => 'Question deleted.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Question not found.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
