<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionCategory;
use App\Enums\QuestionCategoryType;
use App\Http\Requests\StoreQuestionCategoryRequest;
use Illuminate\Support\Facades\DB;

class QuestionCategoryController extends Controller
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
    public function store(StoreQuestionCategoryRequest $request)
    {
        $data = $request->validated();

        $category = DB::transaction(function () use ($data) {
            return QuestionCategory::create([
                'name' => $data['name'],
                'type' => $data['type'],
                'description' => '',
            ]);
        });

        return response()->json(['category' => $category], 201);
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
        $category = QuestionCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Prevent deletion if category has questions
        if ($category->questions()->count() > 0) {
            return response()->json(['message' => 'Cannot delete category with questions. Remove questions first.'], 400);
        }

        try {
            $category->delete();
            return response()->json(['message' => 'Category deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete category'], 500);
        }
    }
}
