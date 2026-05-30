@extends('layouts.admin')

@section('content')
@use(App\Enums\AdminRole)
@use(App\Enums\AdminStatus)
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
            <h2 class="text-[#010767] text-[32px] font-bold">ADMIN PROFILE</h2>
        </div>
        <div class="items-center place-items-center flex flex-col w-full">
            <div class="w-80 my-10 ">
                <div
                    class="rounded-full aspect-square bg-[linear-gradient(180deg,#0B1674_47%,#1529DA_100%)] items-center flex justify-center">
                    <div class="max-w-20 h-auto">
                        <img class="max-w-full h-auto" src="{{ Vite::asset('resources/images/profile.png') }}" alt="">
                    </div>
                </div>
            </div>

            <!-- Eto yung sa profile page -->
            <div class="profile items-left px-10 py-8 bg-[#E2ECFF]/80 rounded-md w-2/3 gap-10 mb-10 {{ $errors->any() ? 'default-hidden' : '' }}" id="ap_profile">
                <div>
                    <!-- dito yung
                    Full name
                    Username
                    Email
                    Access Role
                    Joined date
                    Status -->

                </div>
                <div class="flex flex-col text-[20px] text-[#010767] gap-2">
                    <h5 class="font-bold mb-2">Account Controls</h5>
                    <a href="" onclick="goEdit(); return false;">Edit User Information</a>
                </div>
            </div>

            <!-- Eto yung pag want mag edit -->
            <div class="{{ $errors->any() ? '' : 'default-hidden' }} profile-edit items-left px-10 py-8 bg-[#E2ECFF]/80 rounded-md w-2/3 gap-10 mb-10"
                id="ap_editProfile">    
                <form class="grid grid-rows-3 font-bold gap-10 text-[20px] text-[#010767]" action="{{ route('admin.profile-update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-col items-center justify-center">
                        <input type="file" id="profilePicture" name="profilePicture" accept="image/*"
                            class="-z-1 absolute">
                        <label for="profilePicture" class="flex gap-2 items-center">
                            <div class="max-w-8 h-auto">
                                <img src="{{ Vite::asset('resources/images/circle-camera-2.png') }}" alt="" class="w-100 h-auto">
                            </div>

                            Upload Picture
                        </label>
                    </div>
                    <div class="grid grid-cols-6 gap-10 items-end">
                        <div class="col-span-2 flex flex-col gap-2">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="bg-white rounded-md shadow-md/20">
                            @error('name')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-6 gap-10 items-end">
                        <div class="col-span-2 flex flex-col gap-2">
                            <label for="password">Password</label>
                            <input type="text" name="password" id="password" class="bg-white rounded-md shadow-md/20">
                            @error('password')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-4 flex flex-col gap-2">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" value="{{ Auth::user()->email }}" class="bg-white rounded-md shadow-md/20">
                            @error('email')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-6 gap-10 items-end">
                        <div class="col-span-2 flex flex-col gap-2">
                            <label for="role">Access Role</label>
                            <select name="role" id="role" class="bg-white rounded-md shadow-md/20">
                                <option value=""></option>
                                @foreach (AdminRole::getValues() as $value)
                                    <option value="{{ $value }}" 
                                        @if (Auth::user()->role == $value) selected @endif
                                    >
                                    {{ AdminRole::getDescription($value) }}
                                </option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-2 flex flex-col gap-2">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="bg-white rounded-md shadow-md/20">
                                <option value=""></option>
                                @foreach (AdminStatus::getValues() as $value)
                                    <option value="{{ $value }}" 
                                        @if (Auth::user()->status == $value) selected @endif
                                    >
                                    {{ AdminStatus::getDescription($value) }}
                                </option>
                                @endforeach
                            </select>
                            @error('status')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="w-full items-center flex justify-center mt-5">
                        <button type="submit"
                            class="col-span-2 col-start-3 text-white bg-[linear-gradient(180deg,#02AB27_0%,#014510_100%)] px-5 py-2 rounded-4xl">
                            SAVE CHANGES
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
