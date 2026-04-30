<x-app-layout>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-xl font-semibold text-gray-900 mb-5">Cerere noua</h1>

                <form method="POST" action="{{ route('customer.requests.store') }}">
                    @csrf
                    @include('account.requests._form')

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('customer.requests.index') }}"
                           class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                            Anuleaza
                        </a>
                        <x-primary-button>Posteaza cererea</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
