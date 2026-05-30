@extends('layouts.user')
@section('content')

<div class="flex flex-row">

    <section class="flex flex-col w-full min-h-screen bg-[url('../images/background.jpg')] items-center">
        <div class="w-full h-full grid grid-cols-2">
            <div class="h-full w-full flex items-center justify-center">
                <img src="{{ Vite::asset('resources/images/header_backend.png') }}" alt="">
            </div>
            <div class="h-full w-full flex items-center justify-center">
                <div
                    class="bg-linear-[180deg,#010767_44%,#020ECD_100%] h-[80%] w-[75%] rounded-4xl items-center flex flex-col py-10 px-5">
                    <h1 class="text-linear-gradient2 font-bold text-[40px] text-center">
                        Scan QR for mobile access!
                    </h1>
                    <div class="m-1 w-[50%] h-[50%]">
                        <!-- dito ilalagay ung tamang QR code -->
                        <img src="{{ Vite::asset('resources/images/8776378 2.svg') }}" alt="" class="h-full w-auto">
                        <h3 class="text-[26px] text-linear-gradient2 font-bold text-center">
                            Prefer this device? Start the survey here.
                        </h3>
                        <a href="{{ route('user.survey-consent') }}" class="w-[100%] h-auto items-center flex justify-center mt-5">
                            <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-31.png') }}" alt="" class="w-[80%] h-auto">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
