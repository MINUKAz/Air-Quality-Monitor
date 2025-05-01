@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Manage Sensors</h1>
        <a href="{{ route('sensors.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Add New Sensor</a>
    </div>
    <div class="overflow-x-auto rounded shadow">
        <table class="min-w-full bg-white dark:bg-gray-800">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Name</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Latitude</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Longitude</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Status</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sensors as $sensor)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2">{{ $sensor->name }}</td>
                        <td class="px-4 py-2">{{ $sensor->latitude }}</td>
                        <td class="px-4 py-2">{{ $sensor->longitude }}</td>
                        <td class="px-4 py-2">
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                {{ $sensor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($sensor->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('sensors.edit', $sensor->id) }}" class="inline-block px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition text-xs">Edit</a>
                            <form action="{{ route('sensors.destroy', $sensor->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this sensor?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No sensors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection