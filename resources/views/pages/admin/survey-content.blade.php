@extends('layouts.admin')
@section('content')

<div class="flex flex-row">
    <section class="flex flex-col w-1/4 min-h-screen bg-linear-[180deg,#010767_27%,#162681_66%,#020ECD_100%]">
        <div class="flex flex-col justify-center items-center my-10">
            <div class="max-w-30 h-auto">
                <img class="max-w-full h-auto" src="{{ Vite::asset('resources/images/VALENZUELA-CITY-LOGO-768x768.png') }}" alt="">
            </div>
            <h3 class="text-white text-[25px]">VALidate</h3>
        </div>

        <x-admin.navbar />
    </section>


    <section class="flex flex-col w-full min-h-screen bg-[url('../images/background.jpg')] items-center">
        <!-- Header -->
        <div class="flex w-full bg-[#FFEA01] h-[102px] text-center items-center justify-center">
            <h2 class="text-[#010767] text-[32px] font-bold">SURVEY CONTENT</h2>
        </div>

        <!-- MAIN page: Categories -->
        <div class="px-10 py-8 bg-[#E2ECFF]/80 rounded-md w-9/10 gap-2 my-10 flex flex-col text-[20px]" id="sc_main">
            <h2 class="text-[#010767] font-bold text-[24px] mx-5 mb-5">SURVEY QUESTIONNAIRES</h2>

            @foreach($categories as $category)
                <div class="bg-[#010767] w-full px-6 py-3 rounded-md flex justify-between text-white font-normal shadow-md/20">
                    <h3>{{ $category->name }}</h3>
                    <div class="flex gap-3">
                        <button type="button" onclick="showCategoryQuestions('category_{{ $category->id }}')">
                            <img src="{{ Vite::asset('resources/images/desktop-mobile/edit-12.png') }}" alt="">
                        </button>
                        <button type="button" class="delete-category-btn" data-category-id="{{ $category->id }}" onclick="showDeleteConfirmation('category','{{ $category->id }}')">
                            <img src="{{ Vite::asset('resources/images/desktop-mobile/cross-circle-12.png') }}" alt="">
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Questions per Category -->
        @foreach($categories as $category)
            <div class="question default-hidden px-10 py-8 bg-[#E2ECFF]/80 rounded-md w-9/10 gap-2 my-10 flex flex-col"
                id="category_{{ $category->id }}">
                <div class="text-[#010767] font-bold text-[24px] mx-5 mb-5 flex justify-between h-15 items-center">
                    <h2>{{ $category->name }}</h2>
                    <button type="button" class="add-question-btn" data-category="{{ $category->id }}">
                        <img class="w-auto h-full" src="{{ Vite::asset('resources/images/desktop-mobile/add-4.png') }}" alt="">
                    </button>
                </div>

                @foreach($category->questions as $question)
                    <div class="bg-white w-full px-6 py-3 rounded-md flex flex-col justify-between text-[20px] text-[#010767] font-medium shadow-md/20 mb-4">
                        <div class="flex justify-between font-bold">
                            <h3>{{ $question->name }}</h3>
                            <div class="flex gap-3">
                                <img src="{{ Vite::asset('resources/images/desktop-mobile/edit-17.png') }}" alt="">
                                <button type="button" class="delete-question-btn" data-question-id="{{ $question->id }}" onclick="showDeleteConfirmation('question','{{ $question->id }}')">
                                    <img src="{{ Vite::asset('resources/images/desktop-mobile/cross-circle-17.png') }}" alt="">
                                </button>
                            </div>
                        </div>

                        @if($question->options->count())
                            <h5 class="font-regular mt-3">Options:</h5>
                            @foreach($question->options as $option)
                                <div class="flex gap-2 font-light" data-option-id="{{ $option->id }}">
                                    <input type="radio" name="question_{{ $question->id }}" value="{{ $option->id }}">
                                    <label>{{ $option->description }}</label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </section>

    <!-- MAIN -->
    <button type="button" class="fixed bottom-3 right-3" id="sc_mainAddButton">
        <div class="max-w-30 h-auto">
            <img class="w-full h-auto" src="{{ Vite::asset('resources/images/desktop-mobile/add-4.png') }}" alt="">
        </div>
    </button>

</div>

<!-- Survey creation modal -->
<!-- Category creation modal -->
<div class="hidden text-white text-[20px] w-full h-auto items-center justify-center" id="categoryCreation">
    <div class="absolute top-[50%] left-[50%] transform -translate-x-1/2 -translate-y-1/2 w-[50%]">
        <div class="flex flex-col w-[100%] py-8 px-5 rounded-[20px] bg-[#010767] items-center gap-5">
            <h3 class="font-bold text-white">ADD NEW QUESTION CATEGORY</h3>
            <form id="categoryCreationForm" class="w-full flex flex-col gap-5 px-5">
                <div class="flex gap-3">
                    <label for="newCategoryName" class="whitespace-nowrap text-white">Category Name:</label>
                    <input type="text" id="newCategoryName" class="w-full px-2 py-1 rounded" />
                </div>

                <div class="flex gap-3 items-center">
                    <label for="newCategoryType" class="whitespace-nowrap text-white">Type:</label>
                    <select id="newCategoryType" class="w-full px-2 py-1 rounded text-white bg-transparent" style="-webkit-appearance: none; appearance: none;">
                        @foreach(\App\Enums\QuestionCategoryType::getValues() as $val)
                            @php $key = \App\Enums\QuestionCategoryType::getKey($val); $label = ucwords(strtolower(str_replace('_',' ',$key))); @endphp
                            <option value="{{ $val }}" style="color: #010767;">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full flex justify-between h-[60px]">
                    <button type="button" onclick="goCancelCategory()" class="w-auto h-full px-3 py-2 bg-white text-[#010767] rounded">Cancel</button>

                    <button type="submit" class="w-auto h-full px-3 py-2 bg-white text-[#010767] rounded">Create Category</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="hidden text-white text-[20px] w-full h-auto items-center justify-center" id="surveyCreation"
    data-edit-src="{{ Vite::asset('resources/images/desktop-mobile/edit-17.png') }}"
    data-del-src="{{ Vite::asset('resources/images/desktop-mobile/cross-circle-17.png') }}">
    <div class="absolute top-[50%] left-[50%] transform -translate-x-1/2 -translate-y-1/2 w-[70%]">
        <div class="flex flex-col w-[100%] py-8 px-5 rounded-[20px] bg-[#010767] items-center gap-5">
            <h3 class="font-bold text-white">ADD NEW SURVEY QUESTIONNAIRE</h3>
            <form id="surveyCreationForm" class="w-full flex flex-col gap-5 px-5">
                <div class="flex gap-3">
                    <label for="newQuestionName" class="whitespace-nowrap text-white">Questionnaire Name:</label>
                    <input type="text" id="newQuestionName" class="w-full px-2 py-1 rounded" />
                </div>

                <div id="optionsContainer" class="w-full flex flex-col gap-2">
                    <label class="text-white">Options:</label>
                    <div class="flex gap-2 option-row">
                        <input type="text" class="option-input w-full px-2 py-1 rounded" placeholder="Option 1">
                        <button type="button" id="addOptionBtn" class="px-3 py-1 bg-white text-[#010767] rounded">Add</button>
                    </div>
                </div>

                <div class="w-full flex justify-between h-[60px]">
                    <button type="button" onclick="goCancelAdding()" class="w-auto h-full px-3 py-2 bg-white text-[#010767] rounded">Cancel</button>

                    <button type="submit" class="w-auto h-full px-3 py-2 bg-white text-[#010767] rounded">Add Question</button>
                </div>
            </form>

        </div>
    </div>

<!-- modal behaviour moved to resources/js/user/script.js -->

    <!-- Delete confirmation modal (fixed overlay) -->
    <div class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" id="surveyDeleteConfirmation">
        <div class="mx-auto w-[80%]">
            <div class="flex flex-col w-full py-8 px-5 rounded-[20px] bg-[#011D67] text-white items-center gap-6">
                <h3 class="font-bold">DELETE CONFIRMATION</h3>
                <p class="text-center font-regular">Are you sure you want to delete this? This action cannot be undone.</p>
                <div class="w-full flex justify-between">
                    <button type="button" id="deleteCancelBtn" class="px-4 py-2 bg-white text-[#010767] rounded">Cancel</button>
                    <button type="button" id="deleteConfirmBtn" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
                </div>
            </div>
        </div>
    </div>

@endsection
