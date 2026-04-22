@extends('layouts.admin')

@section('content')

<div class="bg-white p-10 rounded-2xl shadow-lg">

    <h1 class="text-2xl font-bold mb-8 text-gray-800">
        Data User
    </h1>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">

            <thead class="bg-gray-50 border-b text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-4 text-left">Nama</th>
                    <th class="px-4 py-4 text-left">Email</th>
                    <th class="px-4 py-4 text-left">No HP</th>
                    <th class="px-4 py-4 text-left">Alamat</th>
                    <th class="px-4 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50">

                    <td class="px-4 py-5 font-medium">
                        {{ $user->name }}
                    </td>

                    <td class="px-4 py-5">
                        {{ $user->email }}
                    </td>

                    <td class="px-4 py-5">
                        {{ $user->no_tlp }}
                    </td>

                    <td class="px-4 py-5">
                        {{ $user->alamat }}
                    </td>

                    <td class="px-4 py-5 text-center">
                        <a href="{{ route('admin.user.show', $user->id) }}"
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Detail
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <div class="mt-8">
        {{ $users->links() }}
    </div>

</div>

@endsection