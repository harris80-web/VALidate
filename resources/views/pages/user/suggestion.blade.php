@extends('layouts.user')
@section('content')

<div class="flex flex-row">

    <section class="flex flex-col w-full min-h-screen bg-[url('../images/background.jpg')] items-center">

        <!-- HEADER -->
        <div class="flex justify-center h-[15%] w-full bg-linear-[180deg,#030969_12%,#172783_100%] py-4">
            <img src="{{ Vite::asset('resources/images/header2.png') }}" alt="" class="w-auto h-full">
        </div>

        <!-- FORM -->
        <form id="suggestionForm" class="h-full w-full flex flex-col justify-center items-center relative">
            @csrf

            <div
                class="relative gap-10 text-[32px] font-bold py-10 px-15 flex flex-col bg-[#E7F0FF]/80 w-[85%] rounded-[50px] items-center justify-center">

                <div class="flex flex-col gap-10 w-full">
                    <div class="flex ">
                        <div class="flex flex-col gap-5 w-full">
                            <label class="text-linear-gradient whitespace-nowrap">
                                Suggestions on how we can further improve our services (optional):
                            </label>
                            <textarea name="suggestion" class="bg-white shadow-md/20 w-full h-30 items-center"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-10 w-full">
                    <div class="flex ">
                        <div class="flex flex-col gap-5 w-full">
                            <label class="text-linear-gradient whitespace-nowrap">
                                Email Address (optional):
                            </label>
                            <input type="text" name="email" class="bg-white shadow-md/20 w-full h-15 items-center">
                        </div>
                    </div>
                </div>

            </div>
            <div class="w-full flex justify-between mt-5">
                 <!-- BACK BUTTON -->
            <a href="{{ route('user.service-quality') }}" class="relative h-20">
                <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-29.png') }}" alt="">
            </a>

            <!-- SUBMIT BUTTON (opens modal) -->
            <button type="button" class="relative h-20" id="surveySubmit">
                <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-50.png') }}" alt="" class="h-full w-auto">
            </button>
            </div>

           

            <!-- MODAL -->
            <div id="submit-confirmation"
                 class="default-hidden fixed inset-0 w-full h-full bg-[#000000]/20 flex flex-col items-center justify-center">

                <div
                    class="rounded-[70px] text-white h-auto w-[50%] flex flex-col items-center justify-center
                    px-15 py-10 bg-linear-[180deg,#010767_44%,#010A9A_72%,#020ECD_100%] gap-10">

                    <h2 class="text-[32px] font-bold">SUBMIT CONFIRMATION</h2>

                    <p class="overflow-hidden overflow-y-auto text-[32px]/12 font-regular text-center">
                        Are you sure you want to submit your responses? Once submitted, you will not be able to make
                        changes.
                    </p>

                    <div class="flex justify-between w-full">
                        <!-- CLOSE MODAL -->
                        <button type="button" class="h-18 mt-12" id="submit-no">
                            <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-42.png') }}" alt=""
                                class="h-full w-auto">
                        </button>

                        <!-- YES: SUBMIT POST -->
                        <button type="submit" class="h-18 mt-12">
                            <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-43.png') }}" alt=""
                                class="h-full w-auto">
                        </button>
                    </div>

                </div>
            </div>

        </form>
    </section>
</div>

@endsection
