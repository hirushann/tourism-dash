<x-filament-panels::page>
    <x-filament::section>
        {{ $this->form }}
    </x-filament::section>

    <x-filament::section heading="Report Preview">
        @php
            $tours = $this->filtered_tours;
        @endphp

        @if($tours->isEmpty())
            <div class="text-center py-4 text-gray-500">
                No tours found matching the selected criteria.
            </div>
        @else
            <div class="overflow-x-auto ring-1 ring-gray-950/5 dark:ring-white/10 rounded-lg">
                <table class="w-full text-left divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Date</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tour Name</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Driver</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Vehicle</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Mileage (Start/End)</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Fuel Cost</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($tours as $tour)
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-3 text-sm dark:text-gray-200">{{ $tour->created_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-sm font-medium dark:text-gray-200">{{ $tour->tour_name }}</td>
                                <td class="px-4 py-3 text-sm dark:text-gray-200">{{ $tour->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm dark:text-gray-200">{{ $tour->vehicle->plate_number ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm dark:text-gray-200">
                                    {{ $tour->start_mileage }} - 
                                    {{ $tour->end_mileage ?? 'Ongoing' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-red-600 dark:text-red-400">
                                    {{ $tour->fuel_amount ? '$' . number_format($tour->fuel_amount, 2) : 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 pt-4 border-t dark:border-gray-700 text-right text-gray-600 dark:text-gray-300 font-semibold">
                Total Tours: {{ $tours->count() }} <br>
                Total Fuel Cost: ${{ number_format($tours->sum('fuel_amount'), 2) }}
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
