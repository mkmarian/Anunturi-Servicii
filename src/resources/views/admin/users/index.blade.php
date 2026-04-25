<x-admin-layout title="Utilizatori">

    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        {{-- Tabs rol --}}
        <div class="flex gap-2 flex-wrap">
            @foreach(['all'=>['👥','Toți'], 'craftsman'=>['🔧','Meșteșugari'], 'customer'=>['👤','Clienți'], 'admin'=>['🛡','Admini']] as $r => [$icon, $label])
                <a href="{{ route('admin.users.index', ['role'=>$r]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition
                          {{ $role === $r ? 'bg-indigo-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm' }}">
                    {{ $icon }} {{ $label }}
                    <span class="ml-1 text-xs {{ $role === $r ? 'text-indigo-200' : 'text-gray-400' }}">
                        {{ $counts[$r] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Cautare --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2 ml-auto">
            <input type="hidden" name="role" value="{{ $role }}">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Caută după nume / email..."
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 w-64">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Caută</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Utilizator</th>
                    <th class="px-5 py-3 text-left">Rol</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Înregistrat</th>
                    <th class="px-5 py-3 text-left">Acțiuni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $user->role === 'craftsman' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $user->role === 'customer' ? 'bg-gray-100 text-gray-600' : '' }}
                                {{ in_array($user->role, ['admin','moderator']) ? 'bg-purple-100 text-purple-700' : '' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            @if($user->status === 'active')
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Activ</span>
                            @else
                                <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Suspendat</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs">
                            {{ $user->created_at->format('d.m.Y') }}
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-xs text-indigo-600 hover:underline">Detalii</a>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs {{ $user->status === 'active' ? 'text-red-500 hover:text-red-700' : 'text-green-600 hover:text-green-800' }}">
                                            {{ $user->status === 'active' ? 'Suspendă' : 'Activează' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Niciun utilizator găsit.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-5 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>

</x-admin-layout>
