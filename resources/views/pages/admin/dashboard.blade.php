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
            <div class="flex w-full bg-[#FFEA01] h-[102px] text-center items-center justify-center">
                <h2 class="text-[#010767] text-[32px] font-bold">DASHBOARD</h2>
            </div>
            <div class=" px-10 py-8 bg-[#E2ECFF]/80 rounded-md w-9/10 gap-5 my-10 flex flex-col">
                <div class="bg-[#010767] px-5 py-2 grid grid-row-3 gap-5 text-white text-[20px] font-bold text-left">
                    <div class="grid grid-cols-2">
                        <h3 class="text-[#FFEA01]">SUMMARY</h3>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="gap-3">
                            <h4>Total Submissions:</h4>
                            <h4>{{ $totalResponses }}</h4>
                        </div>
                        <div class="gap-3">
                            <h4>Net CC Awareness:</h4>
                            <h4>{{ isset($netCcAwareness) ? $netCcAwareness . '%': '-' }}</h4>
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="gap-3">
                            <h4>Suggestions Recieved:</h4>
                            <h4>{{ $totalSuggestions }}</h4>
                        </div>
                        <div class="gap-3">
                            <h4>Overall SQD's Average Satisfactions:</h4>
                            <h4>{{ isset($overallSqdAverage) ? $overallSqdAverage : '-' }}</h4>
                        </div>
                    </div>
                </div>
                <div class="bg-[#010767] px-5 py-2">
                    <h3 class="text-[#FFEA01]">ANALYTICS</h3>
                    <div class="grid grid-row-2 gap-2">
                        <div class="grid grid-cols-5 gap-2">
                            <div class="col-span-3 bg-white p-3 flex justify-center">
                                <canvas id="ccAwarenessChart" style="width:200px; height:150px;"></canvas>
                            </div>
                            <div class="col-span-2 bg-white p-3 flex justify-center">
                                <canvas id="suggestionsChart" style="width:150px; height:150px;"></canvas>
                            </div>
                        </div>
                        <div class="bg-white p-3 flex justify-center">
                            <canvas id="sqDimensionsChart" style="width:300px; height:150px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="fixed bottom-5 right-0">
            <div
                class="gap-5 rounded-md items-center px-3 h-10 w-60 bg-[linear-gradient(180deg,#FF0612_0%,#B40109_100%)] flex flex-row">
                <h2 class="text-white">EXPORT:</h2>
                <div class="h-8 flex gap-2">
                    <!-- d ko alam anong tag jan sa pag eexport -->
                    <img src="{{ Vite::asset('resources/images/desktop-mobile/file-csv-2.png') }}" alt="">
                    <img src="{{ Vite::asset('resources/images/desktop-mobile/icons8-pdf-48-2.png') }}" alt="">
                    <img src="{{ Vite::asset('resources/images/desktop-mobile/icons8-excel-48-2.png') }}" alt="">
                </div>

            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initSurveyCharts(
                @json($ccLabels),
                @json($ccData),
                @json($suggestionsLabels),
                @json($suggestionsData),
                @json($sqLabels),
                @json($sqData)
            );
        });
    </script>
@endsection
