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

        <section class="flex flex-col w-full min-h-screen bg-[url('/src/assets/background.jpg')] items-center">
        <div class="flex w-full bg-[#FFEA01] h-[102px] text-center items-center justify-center">
            <h2 class="text-[#010767] text-[32px] font-bold">REPORT GENERATOR</h2>
        </div>

        <div class="items-center px-10 py-8 bg-[#E2ECFF]/80 rounded-md w-9/10 gap-2 my-10 flex flex-col text-[20px]">
            <form action="{{ route('admin.report.generate') }}" method="GET">
                <div class="grid grid-cols-15 grid-rows-15 gap-3">

                    {{-- CLIENT TYPE --}}
                    <div class="flex h-10 bg-white items-center rounded-full px-3 text-[#010767] col-span-4">
                        <select name="client_type" class="w-full bg-transparent">
                            <option disabled selected hidden>Client Type</option>

                            @foreach (\App\Enums\RespondentType::getValues() as $value)
                                <option value="{{ $value }}">
                                    {{ \App\Enums\RespondentType::getDescription($value) }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- REGION --}}
                    <div class="flex h-10 bg-white items-center rounded-full px-3 text-[#010767] col-span-6">
                        <select name="region" class="w-full bg-transparent">
                            <option disabled selected hidden>Region of Residence</option>

                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}">
                                    {{ $region->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- DATE --}}
                    <div class="flex h-10 bg-white rounded-full px-3 text-[#010767] col-span-4">
                        <label for="date">Date: </label>
                        <input type="date" id="date" name="date" class="text-black ml-2 bg-transparent">
                    </div>

                    {{-- FILE FORMAT --}}
                    <div class="flex flex-col row-start-4 row-span-5 col-span-4 text-[#010767]">
                        <legend>File Format:</legend>

                        <label class="flex gap-2">
                            <input type="radio" name="format" value="CSV">
                            CSV
                        </label>

                        <label class="flex gap-2">
                            <input type="radio" name="format" value="EXCEL">
                            Excel
                        </label>

                        <label class="flex gap-2">
                            <input type="radio" name="format" value="PDF">
                            PDF
                        </label>

                        <label class="flex gap-2">
                            <input type="radio" name="format" value="ARTA">
                            ARTA
                        </label>
                    </div>

                    <div class="text-left flex flex-col bg-white p-3 text-[#010767] row-start-3 row-span-12 col-span-10 col-start-6">
                        <h1 class="my-2 font-bold">REPORT</h1>

                        @if($respondentsCountByRegion->count() > 0)
                            <div class="h-full bg-gray-200 p-3 overflow-auto">
                                @foreach($respondentsCountByRegion as $r)
                                    <p>
                                        {{ $r->region->name ?? 'Unknown' }} — {{ $r->total }} respondents
                                    </p>
                                @endforeach
                            </div>
                        @else
                            <div class="h-full bg-gray-200 flex justify-center items-center">
                                <p>No respondents found.</p>
                            </div>
                        @endif
                    </div>

                    {{-- SUBMIT --}}
                    <button type="submit" class="col-span-4 row-start-15 row-end-16">
                        <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-39.png') }}" alt="" class="w-50">
                    </button>

                </div>
            </form>

            <!-- D kona alam pano yung table dito -->

        </div>

    </section>


    <div class="fixed bottom-3 right-3">
        <div class="max-w-30 h-auto">
            <img class="w-full h-auto" src="assets/desktop-mobile/add 4.png" alt="">
        </div>

    </div>

</div>
@endsection