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
            <h2 class="text-[#010767] text-[32px] font-bold">USER MANAGEMENT</h2>
        </div>

        <div class=" px-10 py-8 bg-[#E2ECFF]/80 rounded-md w-9/10 gap-2 my-10 flex flex-col text-[20px]">
            <form action="">
                <div class="grid grid-cols-8 gap-3">
                    <div
                        class="flex h-10 bg-white items-center rounded-full px-3 text-[#010767] col-span-4 text-[20px]">
                        <div class="h-8 w-auto">
                            <img class="h-full w-auto" src="{{ Vite::asset('resources/images/search_24dp_010767_FILL0_wght400_GRAD0_opsz24.png') }}"
                                alt="">
                        </div>
                        <input id="um_search" class="bg-transparent w-full opacity-100" type="search" placeholder="Search">
                    </div>

                    <div
                        class="flex h-10 bg-white items-center rounded-full px-3 text-[#010767] col-span-2 text-[20px] items-center">
                        <div class="h-3 w-auto">
                            <img class="h-full w-auto" src="{{ Vite::asset('resources/images/desktop-mobile/Group-1.png') }}" alt="">
                        </div>

                        <select name="um_role" id="um_role"
                            class="bg-white rounded-md shadow-md/20 bg-transparent shadow-none w-full items-center">
                            <option value="" disabled selected hidden>Role</option>
                            @foreach(\App\Enums\AdminRole::getValues() as $val)
                                <option value="{{ \App\Enums\AdminRole::getDescription($val) }}">{{ \App\Enums\AdminRole::getDescription($val) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div
                        class="flex h-10 bg-white items-center rounded-full px-3 text-[#010767] col-span-2 text-[20px] items-center">
                        <select name="um_status" id="um_status"
                            class="bg-white rounded-md shadow-md/20 bg-transparent shadow-none w-full items-center">
                            <option value="" disabled selected hidden>Status</option>
                            @foreach(\App\Enums\AdminStatus::getValues() as $val)
                                <option value="{{ \App\Enums\AdminStatus::getDescription($val) }}">{{ \App\Enums\AdminStatus::getDescription($val) }}</option>
                            @endforeach
                        </select>
                    </div>


                </div>
            </form>
            <div class="w-full bg-white rounded-md p-4">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[#010767]">
                            <th class="px-2 py-2">ID</th>
                            <th class="px-2 py-2">Name</th>
                            <th class="px-2 py-2">Email</th>
                            <th class="px-2 py-2">Role</th>
                            <th class="px-2 py-2">Status</th>
                            <th class="px-2 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                            <tr class="border-t">
                                <td class="px-2 py-2">{{ $admin->id }}</td>
                                <td class="px-2 py-2">{{ $admin->name }}</td>
                                <td class="px-2 py-2">{{ $admin->email }}</td>
                                <td class="px-2 py-2">
                                    {{ $admin->role ? \App\Enums\AdminRole::getDescription($admin->role) : '-' }}
                                </td>
                                <td class="px-2 py-2">
                                    {{ $admin->status ? \App\Enums\AdminStatus::getDescription($admin->status) : '-' }}
                                </td>
                                <td class="px-2 py-2">
                                    <button type="button" class="px-3 py-1 bg-blue-600 text-white rounded edit-admin-btn" data-admin-id="{{ $admin->id }}">Edit</button>
                                    <button type="button" class="px-3 py-1 bg-red-600 text-white rounded delete-admin-btn" data-admin-id="{{ $admin->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>






    </section>

    <button type="button" class="fixed bottom-3 right-3" id="adminAddButton">
        <div class="max-w-30 h-auto">
            <img class="w-full h-auto" src="{{ Vite::asset('resources/images/desktop-mobile/add-4.png') }}" alt="">
        </div>
    </button>



</div>
@endsection

<!-- Admin Add/Edit Modal -->
<div class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" id="adminModal">
    <div class="mx-auto w-[50%]">
        <div class="flex flex-col w-full py-8 px-5 rounded-[20px] bg-[#010767] text-white items-center gap-6">
            <h3 class="font-bold" id="adminModalTitle">Add Admin</h3>
            <form id="adminForm" class="w-full flex flex-col gap-4">
                <input type="hidden" id="adminId" />
                <div class="flex gap-3">
                    <label class="whitespace-nowrap">Name:</label>
                    <input id="adminName" type="text" class="w-full px-2 py-1 rounded text-white" />
                </div>
                <div class="flex gap-3">
                    <label class="whitespace-nowrap">Email:</label>
                    <input id="adminEmail" type="email" class="w-full px-2 py-1 rounded text-white" />
                </div>
                <div class="flex gap-3">
                    <label class="whitespace-nowrap">Password:</label>
                    <input id="adminPassword" type="password" class="w-full px-2 py-1 rounded text-white" />
                </div>
                <div class="flex gap-3">
                    <label class="whitespace-nowrap">Role:</label>
                    <select id="adminRole" class="w-full px-2 py-1 rounded text-white">
                        @foreach(\App\Enums\AdminRole::getValues() as $val)
                            <option value="{{ $val }}" style="color: #010767;">{{ \App\Enums\AdminRole::getDescription($val) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <label class="whitespace-nowrap">Status:</label>
                    <select id="adminStatus" class="w-full px-2 py-1 rounded text-white">
                        @foreach(\App\Enums\AdminStatus::getValues() as $val)
                            <option value="{{ $val }}" style="color: #010767;">{{ \App\Enums\AdminStatus::getDescription($val) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full flex justify-between">
                    <button type="button" id="adminCancelBtn" class="px-4 py-2 bg-white text-[#010767] rounded">Cancel</button>
                    <button type="submit" id="adminSaveBtn" class="px-4 py-2 bg-white text-[#010767] rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
 </div>
