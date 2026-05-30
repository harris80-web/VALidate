@extends('layouts.user')
@section('content')

<div class="flex flex-row">
    <section class="flex flex-col w-full min-h-screen bg-[url('../images/background.jpg')] items-end">
        <div class="flex justify-center h-[15%] w-full bg-linear-[180deg,#030969_12%,#172783_100%] py-4">
            <img src="{{ Vite::asset('resources/images/header2.png') }}" alt="" class="w-auto h-full">
        </div>
        <form action="{{ route('user.submit-citizens-charter') }}" method="POST" class=" h-full w-full flex flex-col justify-center items-center">
            @csrf
            <div
                class="relative text-[#010767] gap-10 text-[32px] py-10 px-10 flex flex-col bg-[#E7F0FF]/80 w-[85%] rounded-[50px] items-center justify-center">
                <p class="text-[32px]">
                    <b>INSTRUCTIONS:</b> Please place a <b>Check Mark (✓ )</b> in the designated box that corresponds to
                    your answer on the Citizen’s Charter (CC) questions. The Citizen’s Charter is an official document
                    that reflects the services of a government agency/office including its requirements, fees, and
                    processing times among others.
                </p>

                <!-- format per question -->
                @foreach($questions as $question)
                    <div class="w-full">
                        <div
                            class="relative -left-10 text-white font-bold flex justify-center items-center bg-linear-[180deg,#EA3323_0%,#841D14_100%] w-[10%] min-w-[114px]">
                            <h5>{{ 'CC' . $loop->iteration }}</h5>
                        </div>
                        <div>
                            <h4 class="font-bold">{{ $question->name }}</h4>
                            <div class="flex flex-col">
                                @foreach($question->options as $option)
                                    <div class="flex gap-3">
                                        <input
                                            type="radio"
                                            name="question_{{ $question->id }}"
                                            id="question_{{ $question->id }}_option_{{ $option->id }}"
                                            value="{{ $option->id }}"
                                            {{ old('question_' . $question->id, session('form.stepper.citizens_charter_questions.question_' . $question->id)) == $option->id ? 'checked' : '' }}
                                        >
                                        <label for="question_{{ $question->id }}_option_{{ $option->id }}">
                                            {{ $option->description }}
                                        </label>
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
                <a href="{{ route('user.survey-start') }}" class="relative h-20">
                <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-29.png') }}" alt="">
            </a>
            <button type="submit" class="relative h-20">
                <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-30.png') }}" alt="" class="h-full w-auto">
            </button>
            </div>
            
        </form>
    </section>
</div>
@endsection
