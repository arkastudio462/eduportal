<x-layouts.admin title="Aktivitas Sistem">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Aktivitas Sistem</h2>
        <p class="text-on-surface-variant font-body-md">Log aktivitas pengguna dalam sistem</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-on-primary">
                    <th class="text-left py-3 px-4 font-label-md">Waktu</th>
                    <th class="text-left py-3 px-4 font-label-md">Pengguna</th>
                    <th class="text-left py-3 px-4 font-label-md">Aksi</th>
                    <th class="text-left py-3 px-4 font-label-md">Deskripsi</th>
                    <th class="text-left py-3 px-4 font-label-md">IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                <tr class="border-t border-outline-variant hover:bg-surface-bright transition-colors">
                    <td class="py-3 px-4 text-on-surface-variant whitespace-nowrap">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3 px-4">{{ $activity->causer?->name ?? 'Sistem' }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                            @switch($activity->event ?? $activity->description)
                                @case('created') bg-green-100 text-green-700 @break
                                @case('updated') bg-blue-100 text-blue-700 @break
                                @case('deleted') bg-red-100 text-red-700 @break
                                @default bg-gray-100 text-gray-700
                            @endswitch">{{ $activity->event ?? class_basename($activity->subject_type) }}</span>
                    </td>
                    <td class="py-3 px-4">{{ $activity->description }}</td>
                    <td class="py-3 px-4 text-on-surface-variant text-xs">{{ $activity->properties->get('ip', '-') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-outline">Belum ada aktivitas tercatat</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($activities->hasPages())
    <div class="p-4 border-t border-outline-variant">
        {{ $activities->links() }}
    </div>
    @endif
</div>
</x-layouts.admin>
