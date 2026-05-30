@extends('layouts.user')
@section('content')

<div class="flex flex-row">

    <section class="flex flex-col w-full min-h-screen bg-[url('../images/background.jpg')] items-center">
        <div class="flex justify-center h-[15%] w-full bg-linear-[180deg,#030969_12%,#172783_100%] py-4">
            <img src="{{ Vite::asset('resources/images/header2.png') }}" alt="" class="w-auto h-full">
        </div>
        <div class=" h-full w-full flex justify-center items-center">
            <div
                class="gap-10 px-10 flex text-center flex-col bg-[#E7F0FF]/80 w-[85%] h-[55%] rounded-[50px] items-center justify-center">
                <h3 class="text-[32px] font-bold text-linear-gradient">
                    ARTA-COMPLIANT CUSTOMER SATISFACTION SURVEY
                </h3>
                <h4 class="overflow-hidden overflow-y-auto text-[#010767] text-[32px] font-normal">
                    This Client Satisfaction Measurement (CSM) tracks the customer experience of government offices.
                    Your feedback on your recently concluded transaction will help this office provide a better service.
                    Personal information shared will be kept confidential and you always have the option to not answer
                    this form.
                </h4>
            </div>
        </div>
        <button type="button" class="absolute bottom-3 right-2 h-20" onclick="openConsentForm()">
            <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-30.png') }}" alt="" class="h-full w-auto">
        </button>
        <div class="default-hidden fixed w-full h-full bg-[#000000]/20 flex flex-col items-center justify-center"
            id="consent-form">
            <div
                class="rounded-[70px] text-white h-[55%] w-[70%] flex flex-col items-center justify-center px-15 py-15 bg-linear-[180deg,#010767_44%,#010A9A_72%,#020ECD_100%] gap-5">
                <h2 class="text-[32px] font-bold">
                    DATA PRIVACY AND CONSENT FORM
                </h2>
                <div class="h-full overflow-hidden overflow-y-auto py-20">
                    <p class=" text-[26px]/12 font-light">
                    In accordance with the Data Privacy Act of 2012 (RA 10173), the City Government of Valenzuela,
                    through VALidate, is committed to protecting your personal information and survey responses. All
                    data collected will be kept confidential and used exclusively to assess and enhance public service
                    delivery following ARTA guidelines. Your information will be securely stored and accessible only to
                    authorized personnel. Survey findings will be summarized or anonymized to ensure your privacy. <br>
                    By selecting “I Agree” or continuing with VALidate, you confirm your voluntary consent to the
                    collection and processing of your data for evaluation purposes. You may withdraw from participation
                    at any time.
                </p>
                </div>
                
                <a href="{{ route('user.survey-start') }}" class="h-20 mt-5">
                    <img src="{{ Vite::asset('resources/images/desktop-mobile/Group-49.png') }}" alt="" class="h-full w-auto">
                </a>
            </div>
        </div>
    </section>



</div>
@endsection
