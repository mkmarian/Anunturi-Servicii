<x-admin-layout title="Categorii servicii">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Categorii</h2>
            <p class="text-sm text-gray-500 mt-0.5">Gestionează categoriile de servicii afișate pe site</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Categorie nouă
        </a>
    </div>

    @if($categories->isEmpty())
        <div class="text-center py-16 text-gray-400 bg-white rounded-xl border border-gray-200">
            <p class="text-4xl mb-2">🗂️</p>
            <p>Nicio categorie creată încă.</p>
        </div>
    @else
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Categorie</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Slug</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Ordine</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Anunțuri</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($categories as $cat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @if($cat->icon)
                                        <span class="text-xl">{{ $cat->icon }}</span>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $cat->name }}</p>
                                        @if($cat->parent_id)
                                            <p class="text-xs text-gray-400">subcategorie</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell text-xs text-gray-400 font-mono">{{ $cat->slug }}</td>
                            <td class="px-4 py-3 hidden lg:table-cell text-center text-sm text-gray-500">{{ $cat->sort_order }}</td>
                            <td class="px-4 py-3 hidden lg:table-cell text-center text-sm font-medium text-gray-700">{{ $cat->listings_count }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($cat->is_active)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-50 text-green-700 border border-green-200">Activă</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-500 border border-gray-200">Inactivă</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.categories.edit', $cat) }}"
                                       class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">Editează</a>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                          onsubmit="return confirm('Ștergi categoria \'{{ addslashes($cat->name) }}\'?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Șterge</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
