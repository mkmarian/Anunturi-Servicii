<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cerere noua de serviciu</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-5">
                    Descrie ce serviciu ai nevoie. Cererea ta va fi vizibila meseriasilor din zona aleasa,
                    care te vor putea contacta direct.
                </p>

                <form method="POST" action="{{ route('customer.requests.store') }}">
                    @csrf
                    @include('account.requests._form')

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('customer.requests.index') }}"
                           class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                            Anuleaza
                        </a>
                        <x-primary-button>Trimite cererea</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
