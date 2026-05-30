@extends('layouts.user')

@section('content')

@use(App\Enums\RespondentType)
@use(App\Enums\Gender)

<div class="flex flex-row">

    <section class="flex flex-col w-full min-h-screen bg-[url('../images/background.jpg')] items-center">
        <div class="flex justify-center h-[15%] w-full bg-linear-[180deg,#030969_12%,#172783_100%] py-4">
            <img src="{{ Vite::asset('resources/images/header2.png') }}" alt="" class="w-auto h-full">
        </div>

        <form action="{{ route('user.submit-survey-start') }}" method="POST" class="h-full w-full flex flex-col justify-center items-center">
            @csrf

            <div class="relative gap-10 text-[32px] font-bold py-10 px-10 flex flex-col bg-[#E7F0FF]/80 w-[85%] rounded-[50px] items-center justify-center">

                <div class="absolute h-[54px] px-3 py-2 bg-linear-[180deg,#EA3323_0%,#841D14_100%] rounded-[50px] -top-[27px] -right-[1px]">
                    <h3 class="text-[24px] font-bold text-white">Control No:</h3>
                </div>

                <div class="flex flex-col gap-10 w-full">

                    <!-- CLIENT TYPE -->
                    <div class="gap-2">
                        <h2 class="text-linear-gradient text-left">CLIENT TYPE:</h2>

                        <div class="flex gap-5 font-light text-linear-gradient">
                            @foreach (RespondentType::asArray() as $key => $value)
                                <div class="flex gap-2 items-center">
                                    <input
                                        type="radio"
                                        name="type"
                                        id="{{ strtolower($value) }}"
                                        value="{{ $value }}"
                                        {{ (old('type', session('form.stepper.survey_start.type')) == $value) ? 'checked' : '' }}
                                    >
                                    <label for="{{ strtolower($value) }}">
                                        {{ RespondentType::getDescription($value) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        @error('type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- DATE + AGE -->
                    <div class="flex gap-10 justify-between w-full">

                        <div class="flex relative gap-2">
                            <label class="text-linear-gradient">DATE:</label>

                            <div class="relative flex gap-3 bg-[#FFFFFF] z-5">
                                <input 
                                    type="date" 
                                    name="submitted_date" 
                                    value="{{ old('submitted_date', session('form.stepper.survey_start.submitted_date')) }}" 
                                    min="{{ date('Y-m-d') }}" 
                                    class="relative z-10">
                            </div>
                        </div>

                        @error('submitted_date')
                            <p class="text-red-600 text-sm -mt-4">{{ $message }}</p>
                        @enderror

                        <div class="flex relative gap-2">
                            <label class="text-linear-gradient">AGE:</label>

                            <div class="relative flex gap-3 z-5">
                                <input 
                                    type="number" 
                                    name="age" 
                                    min="1" 
                                    value="{{ old('age', session('form.stepper.survey_start.age')) }}" 
                                    class="relative z-10 bg-[#FFFFFF] min-w-[75px] w-[40%]">
                            </div>
                        </div>

                        @error('age')
                            <p class="text-red-600 text-sm -mt-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SEX -->
                    <div class="flex">
                        <div class="flex gap-3">
                            <label class="text-linear-gradient">SEX:</label>

                            <select name="gender" class="bg-white shadow-md/20 shadow-none w-full items-center">
                                <option value="">Select</option>

                                @foreach (Gender::asArray() as $key => $value)
                                    <option value="{{ $value }}" 
                                        {{ old('gender', session('form.stepper.survey_start.gender')) == $value ? 'selected' : '' }}>
                                        {{ Gender::getDescription($value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @error('gender')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- REGION -->
                    <div class="flex">
                        <div class="flex gap-3 w-[80%]">
                            <label for="region" class="text-linear-gradient whitespace-nowrap">REGION OF RESIDENCE:</label>

                            <select name="region" id="region" class="bg-white shadow-md/20 shadow-none w-full items-center">
                                <option value="">-- Select a Region --</option>

                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}" 
                                        {{ old('region', session('form.stepper.survey_start.region')) == $region->id ? 'selected' : '' }}>
                                        {{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @error('region')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- SERVICE AVAILED -->
                    <div class="flex">
                        <div class="flex gap-3 w-full">
                            <label class="text-linear-gradient whitespace-nowrap">SERVICE AVAILED:</label>

                            <textarea 
                                name="service" 
                                class="bg-white shadow-md/20 shadow-none w-full h-30 items-center">{{ old('service', session('form.stepper.survey_start.service')) }}</textarea>
                        </div>
                    </div>

                    @error('service')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>
            </div>
            <div class="w-full flex justify-end mt-5">
                <button type="submit" id="clientType-submit" class="relative h-20">
                <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-30.png') }}" alt="" class="h-full w-auto">
            </button>
            </div>
            

        </form>
    </section>
</div>

@endsection
