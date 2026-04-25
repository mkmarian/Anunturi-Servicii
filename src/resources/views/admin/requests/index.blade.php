<x-admin-layout title="Cereri servicii">

    {{-- Tabs status --}}
    <div class="flex gap-2 mb-6 flex-wrap">
        @foreach(['pending'=>['⏳','Pending','yellow'], 'published'=>['✅','Aprobate','green'], 'rejected'=>['❌','Respinse','red'], 'all'=>['📋','Toate','gray']] as $s => [$icon, $label, $color])
            <a href="{{ route('admin.requests.index', ['status'=>$s]) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition
                      {{ $status === $s ? 'bg-indigo-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm' }}">
                {{ $icon }} {{ $label }}
                <span class="ml-1 text-xs {{ $status === $s ? 'text-indigo-200' : 'text-gray-400' }}">
                    {{ $counts[$s] }}
                </span>
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Titlu</th>
                    <th class="px-5 py-3 text-left">Utilizator</th>
                    <th class="px-5 py-3 text-left">Categorie / Județ</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Data</th>
                    <th class="px-5 py-3 text-left">Acțiuni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($requests as $sr)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-900 max-w-xs">
                            <a href="{{ route('admin.requests.show', $sr) }}" class="hover:text-indigo-600 line-clamp-1">
                                {{ $sr->title }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $sr->user->name }}</td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ $sr->category->name ?? '—' }}<br>
                            <span class="text-xs">{{ $sr->county->name ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3">
                            @if($sr->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">Pending</span>
                            @elseif($sr->status === 'published')
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">Aprobat</span>
                            @else
                                <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">Respins</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs whitespace-nowrap">
                            {{ $sr->created_at->format('d.m.Y') }}
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                @if($sr->status === 'pending')
                                    <form method="POST" action="{{ route('admin.requests.approve', $sr) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">Aprobă</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.requests.reject', $sr) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Respinge</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.requests.destroy', $sr) }}"
                                      onsubmit="return confirm('Ștergi cererea?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-gray-400 hover:text-red-500">✕</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Nicio cerere găsită.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-5 py-4 border-t border-gray-100">
            {{ $requests->links() }}
        </div>
    </div>

</x-admin-layout>
