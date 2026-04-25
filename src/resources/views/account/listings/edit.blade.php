<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editeaza: {{ $listing->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('craftsman.listings.update', $listing) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('account.listings._form')

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('craftsman.listings.index') }}"
                           class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                            Anuleaza
                        </a>
                        <x-primary-button>Salveaza modificarile</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
