<x-filament-panels::page>
    @php($trips = $this->getTrips())
    <div class="space-y-6">
        @forelse($trips as $trip)
            <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-mono text-gray-400">{{ $trip->route->code }}</p>
                        <h3 class="text-lg font-semibold">{{ $trip->route->start_location }} → {{ $trip->route->end_location }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $trip->departure_time->format('D, j M · H:i') }} ·
                            {{ $trip->vehicle?->plate_number ?? '—' }} ·
                            {{ str_replace('_', ' ', $trip->status) }}
                        </p>
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-gray-500">Manifest</p>
                        <p class="font-semibold">{{ $trip->seats_booked }} / {{ $trip->seats_total }}</p>
                    </div>
                </div>

                <details class="mt-4">
                    <summary class="cursor-pointer text-sm text-emerald-700 font-medium">View passenger manifest</summary>
                    <table class="mt-3 w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="py-2 pr-4">Passenger</th>
                                <th class="py-2 pr-4">Seats</th>
                                <th class="py-2 pr-4">PIN</th>
                                <th class="py-2 pr-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trip->bookings as $booking)
                                <tr class="border-t border-gray-100 dark:border-white/10">
                                    <td class="py-2 pr-4">{{ $booking->user->name }}</td>
                                    <td class="py-2 pr-4">{{ implode(', ', $booking->seats ?? []) }}</td>
                                    <td class="py-2 pr-4 font-mono">{{ $booking->boarding_pin }}</td>
                                    <td class="py-2 pr-4">{{ str_replace('_', ' ', $booking->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </details>
            </div>
        @empty
            <p class="text-gray-500">No assigned trips right now.</p>
        @endforelse
    </div>
</x-filament-panels::page>
