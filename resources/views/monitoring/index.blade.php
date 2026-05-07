@extends('layouts.app')
@section('title', 'Monitoring')
@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="font-medium text-gray-800">Data Monitoring Sensor</h2>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3 text-left">Perangkat</th>
                <th class="px-6 py-3 text-left">Lokasi</th>
                <th class="px-6 py-3 text-left">Suhu</th>
                <th class="px-6 py-3 text-left">Kelembaban</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Terakhir Update</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($devices ?? [] as $device)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $device->name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $device->location }}</td>
                <td class="px-6 py-4">{{ $device->temperature ? $device->temperature.'°C' : '—' }}</td>
                <td class="px-6 py-4">{{ $device->humidity ? $device->humidity.'%' : '—' }}</td>
                <td class="px-6 py-4">
                    @if($device->status === 'online')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Online</span>
                    @elseif($device->status === 'warning')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Warning</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">Offline</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-400">{{ $device->updated_at->diffForHumans() }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-400">Belum ada data monitoring.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
