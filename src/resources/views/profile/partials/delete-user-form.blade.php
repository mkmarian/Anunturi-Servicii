<div class="space-y-4">
    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
>Șterge contul</x-danger-button>
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                Ești sigur că vrei să îți ștergi contul?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Odată șters, contul și toate datele tale vor fi șterse permanent. Introdu parola pentru confirmare.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Parola"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Anulează
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Șterge contul
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</div>
