<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Cerere nouă</h1>
                <p class="text-sm text-gray-500 mt-1">Descrie ce serviciu cauți și așteaptă oferte de la meseriași</p>
            </div>
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                <form method="POST" action="{{ route('customer.requests.store') }}">
                    @csrf
                    @include('account.requests._form')

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('customer.requests.index') }}"
                           class="px-4 py-2 text-sm text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                            Anulează
                        </a>
                        <x-primary-button>Postează cererea</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
