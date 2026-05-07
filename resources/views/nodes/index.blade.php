@extends('layouts.app')
@section('title', 'Kelola Node')
@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h2 class="font-medium text-gray-800">Daftar Node</h2>
        <a href="{{ route('nodes.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
            + Tambah Node
        </a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3 text-left">Nama Node</th>
                <th class="px-6 py-3 text-left">Lokasi</th>
                <th class="px-6 py-3 text-left">Deskripsi</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($nodes ?? [] as $node)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $node->name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $node->location }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $node->description ?? '—' }}</td>
                <td class="px-6 py-4">
                    @if($node->status === 'active')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">Nonaktif</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-2">
                        <a href="{{ route('nodes.edit', $node) }}" class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-100">Edit</a>
                        <form action="{{ route('nodes.destroy', $node) }}" method="POST" onsubmit="return confirm('Yakin hapus node ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs border border-red-200 rounded-lg text-red-500 hover:bg-red-50">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                    Belum ada node. <a href="{{ route('nodes.create') }}" class="text-indigo-600 underline">Tambah sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
