<x-admin-layout title="Cereri Meseriaș">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-900">Cereri Meseriaș</h1>
        @if($counts['pending'] > 0)
            <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">
                {{ $counts['pending'] }} în așteptare
            </span>
        @endif
    </div>

    {{-- Filtre status --}}
    <div class="flex gap-2 mb-6 flex-wrap">
        @foreach(['pending' => ['label' => 'În așteptare', 'color' => 'yellow'], 'approved' => ['label' => 'Aprobate', 'color' => 'green'], 'rejected' => ['label' => 'Respinse', 'color' => 'red'], 'all' => ['label' => 'Toate', 'color' => 'gray']] as $s => $meta)
            <a href="{{ request()->fullUrlWithQuery(['status' => $s]) }}"
               class="px-4 py-1.5 rounded-lg text-sm font-medium border transition
                      {{ $status === $s
                           ? 'bg-indigo-600 text-white border-indigo-600'
                           : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300' }}">
                {{ $meta['label'] }}
                @if(isset($counts[$s]))
                    <span class="ml-1 text-xs opacity-75">({{ $counts[$s] }})</span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        @if($applications->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <p class="text-4xl mb-3">📭</p>
                <p class="text-sm">Nicio cerere în această categorie.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Utilizator</th>
                        <th class="px-4 py-3 text-left">Categorie</th>
                        <th class="px-4 py-3 text-left">Experiență</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Data</th>
                        <th class="px-4 py-3 text-left">Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($applications as $application)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900">{{ $application->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $application->user->email }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $application->category?->icon }} {{ $application->category?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $application->experience_years }} ani
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $colors = ['pending' => 'amber', 'approved' => 'green', 'rejected' => 'red'];
                                    $c = $colors[$application->status] ?? 'gray';
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    bg-{{ $c }}-100 text-{{ $c }}-700">
                                    {{ $application->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">
                                {{ $application->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.craftsman-applications.show', $application) }}"
                                   class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                    Vezi detalii →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($applications->hasPages())
                <div class="px-4 py-4 border-t border-gray-100">
                    {{ $applications->links() }}
                </div>
            @endif
        @endif
    </div>

</x-admin-layout>
