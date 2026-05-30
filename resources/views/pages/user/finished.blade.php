@extends('layouts.user')
@section('content')


<div class="flex flex-row">

    <section class="flex flex-col w-full min-h-screen bg-[url('../images/background.jpg')] items-center">
        <div class="flex justify-center h-[15%] w-full bg-linear-[180deg,#030969_12%,#172783_100%] py-4">
            <img src="{{ Vite::asset('resources/images/header2.png') }}" alt="" class="w-auto h-full">
        </div>
        <div class=" h-full w-full flex justify-center items-center">

            <div action=""
                class=" relative gap-10 text-[32px] font-bold py-10 px-15 flex flex-col bg-[#E7F0FF]/80 w-[85%] rounded-[50px] items-center justify-center">
                <div class="text-[#010767] text-center flex flex-col gap-5">
                    <h3 class="text-linear-gradient ">THANK YOU!</h3>
                    <p class="font-regular">
                        Your responses have been recorded. Thank you for helping us serve you better.
                    </p>

                    <!-- di ko alam ko ano to -->
                    <h6 class="text-linear-gradient text-[24px] mt-5">OP-VAL-HR-1 010 F20</h6>
                </div>

            </div>

        </div>
        <a href="{{ route('user.index') }}" class="relative bottom-3 h-20" id="">
            <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-53.png') }}" alt="" class="h-full w-auto">
        </a>


    </section>



</div>

@endsection
