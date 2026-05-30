@extends('layouts.admin')
@section('content')
    <div>
        <header class="">
            <section class="h-screen w-screen grid grid-cols-2">
                <div class="bg-[url('../images/background.jpg')] flex justify-center items-center">
                    <div class="bg-[#E2ECFF]/80 w-9/10 h-3/5 rounded-[30px]">
                        <form method="POST" action="{{ route('admin.authenticate') }}" class="flex flex-col px-10 gap-5 py-3 text-[32px]">
                            @csrf
                            <h1 class="text-center text-[32px] font-bold my-2 text-linear-gradient">CGOV ADMIN LOGIN
                            </h1>
                            <div class="flex flex-col text-left">
                                <label for="email" class="text-left text-[#010767]">Email:</label>
                                <input type="text" id="email" name="email"
                                    class="bg-white shadow-md/30 rounded-sm">
                            </div>
                            @error('email')
                                <div class="label -mt-4 mb-2">
                                    <span class="label-text-alt text-base text-red-700">{{ $message }}</span>
                                </div>
                            @enderror
                            <div class="flex flex-col text-left">
                                <label for="emailUsername" class="text-left text-[#010767]">Password:</label>
                                <input type="password" id="password" name="password"
                                    class="bg-white shadow-md/30 rounded-sm">
                            </div>
                            @error('password')
                                <div class="label -mt-4">
                                    <span class="label-text-alt text-base text-red-700">{{ $message }}</span>
                                </div>
                            @enderror
                            <div class="flex flex-col text-left gap-3 items-start">
                                <!-- Full width button -->
                                <button
                                    class="bg-gradient-to-b from-[#010767] via-[#162681] to-[#020ECD] rounded-full text-white font-medium w-full py-2 mt-5">
                                    LOGIN
                                </button>

                                <!-- Text-fit link -->
                                {{-- <a href="#" class="text-[20px] text-gray-500 hover:underline">
                                    FORGOT PASSWORD?
                                </a> --}}
                            </div>

                        </form>
                    </div>

                </div>
                <div
                    class="bg-linear-[180deg,#010767_27%,#162681_66%,#020ECD_100%] flex flex-col justify-center items-center gap-5">
                    <div class="max-w-110 h-auto">
                        <img class="max-w-full h-auto"
                            src="{{ Vite::asset('resources/images/VALENZUELA-CITY-LOGO-768x768.png') }}" alt="">
                    </div>

                    <h1 class="font-georgia text-[50px] font-medium text-white">VALidate</h1>
                </div>
            </section>
        </header>
    </div>
@endsection
