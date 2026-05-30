@extends('layouts.user')
@section('content')


<div class="flex flex-row">

    <section class="flex flex-col w-full min-h-screen bg-[url('../images/background.jpg')] items-end">
        <div class="flex justify-center h-[15%] w-full bg-linear-[180deg,#030969_12%,#172783_100%] py-4">
            <img src="{{ Vite::asset('resources/images/header2.png') }}" alt="" class="w-auto h-full">
        </div>
        <form action="{{ route('user.submit-service-quality') }}" method="POST" class=" h-full w-full flex flex-col justify-center items-center">
            @csrf
            <div
                class="relative text-[#010767] gap-10 text-[32px] py-10 px-10 flex flex-col bg-[#E7F0FF]/80 w-[85%] rounded-[50px] items-center justify-center">
                <p class="text-[32px]">
                    <b>INSTRUCTIONS: For SQD 0-8, please select the option that best corresponds to your answer.</b>
                </p>

                <!-- format per question -->
               @foreach($questions as $index => $question)
                    <div class="w-full gap-5 flex flex-col">
                        <div class="relative -left-10 text-white font-bold flex justify-center items-center bg-linear-[180deg,#010767_0%,#1D35E0_100%] w-[10%] min-w-[114px]">
                            <h5>{{ 'SQD' . $loop->iteration }}</h5>
                        </div>
                        <div>
                            <h4 class="font-bold mt-3">{{ $question->name }}</h4>
                            <div class="font-bold my-15 h-[20px] rounded-[20px] grid grid-cols-6 w-full bg-linear-[90deg,#EB1400_11%,#F38E00_26%,#0153E3_46%,#C4E602_57%,#55CB06_78%,#008334_93%]">

                                @php
                                    $values = [
                                        ['label' => 'Strongly Disagree', 'color' => '#EB1400', 'img' => 'Group-57.png'],
                                        ['label' => 'Strongly Disagree', 'color' => '#EB1400', 'img' => 'Group-57.png'],
                                        ['label' => 'Disagree', 'color' => '#F38E00', 'img' => 'Group-70.png'],
                                        ['label' => 'Neutral', 'color' => '#010767', 'img' => 'Group-73.png'],
                                        ['label' => 'Agree', 'color' => '#C4E602', 'img' => 'Group-60.png'],
                                        ['label' => 'Strongly Agree', 'color' => '#55C325', 'img' => 'Group-71.png'],
                                        ['label' => 'Not Applicable', 'color' => '#158B45', 'img' => 'Group-62.png'],
                                    ];
                                @endphp

                                @foreach($question->options as $option)
                                    @php
                                        $valueData = $values[$loop->iteration] ?? ['label' => 'Option', 'color' => '#000', 'img' => 'default.png'];
                                        $oldValue = old('question_' . $question->id, session('form.stepper.service_quality_dimension_questions.question_' . $question->id));
                                    @endphp
                                    <div class="sqd-button text-[20px] relative -top-10 flex flex-col gap-3 items-center justify-start text-center">
                                        <input
                                            type="radio"
                                            name="question_{{ $question->id }}"
                                            id="question_{{ $question->id }}_option_{{ $option->id }}"
                                            class="appearance-none"
                                            value="{{ $option->id }}"
                                            {{ $oldValue == $option->id ? 'checked' : '' }}
                                        >
                                        <label for="question_{{ $question->id }}_option_{{ $option->id }}">
                                            <img src="{{ Vite::asset('resources/images/desktop-mobile/' . $valueData['img']) }}" alt="" class="h-auto">
                                        </label>
                                        <span style="color: {{ $valueData['color'] }}">{{ $valueData['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>

                            @error('question_' . $question->id)
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="w-full flex flex-row justify-between my-5">
                <a href="{{ route('user.citizens-charter') }}" class="relative bottom-3 left-2 h-20">
                <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-29.png') }}" alt="">
            </a>
            <button type="submit" class="relative bottom-3 right-0 h-20 ">
                <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-30.png') }}" alt="" class="h-full w-auto">
            </button>
            </div>
            
        </form>

    </section>



</div>
@endsection
