<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <input type="hidden" name="role" value="customer">

        <!-- Nume -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Nume')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Adresa email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" oninput="checkEmail()" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p id="email-msg" class="mt-1 text-sm hidden"></p>
        </div>

        <!-- Telefon -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Telefon')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" autocomplete="tel" oninput="checkPhone()" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            <p id="phone-msg" class="mt-1 text-sm hidden"></p>
        </div>

        <!-- Parola -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Parola')" />
            <div class="relative mt-1">
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="block w-full pr-10 py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <button type="button" onclick="togglePassword('password', this)"
                        style="position:absolute; top:50%; right:0.625rem; transform:translateY(-50%); background:none; border:none; padding:0; cursor:pointer; color:#9ca3af; line-height:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmare parola -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmare parola')" />
            <div class="relative mt-1">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" oninput="checkMatch()"
                       class="block w-full pr-10 py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <button type="button" onclick="togglePassword('password_confirmation', this)"
                        style="position:absolute; top:50%; right:0.625rem; transform:translateY(-50%); background:none; border:none; padding:0; cursor:pointer; color:#9ca3af; line-height:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            <p id="match-msg" class="mt-1 text-sm hidden"></p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Ai deja cont?') }}
            </a>
            <x-primary-button class="ms-4">
                {{ __('Creeaza cont') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        let emailTimer = null;
        let phoneTimer = null;

        function checkPhone() {
            const phone = document.getElementById('phone').value.trim();
            const msg   = document.getElementById('phone-msg');
            clearTimeout(phoneTimer);
            if (!phone) { msg.classList.add('hidden'); return; }
            phoneTimer = setTimeout(() => {
                fetch('/api/check-phone?phone=' + encodeURIComponent(phone))
                    .then(r => r.json())
                    .then(data => {
                        if (data.taken) {
                            msg.textContent = '✗ Există deja un cont cu acest număr';
                            msg.className = 'mt-1 text-sm text-red-500';
                        } else {
                            msg.textContent = '✓ Numărul este disponibil';
                            msg.className = 'mt-1 text-sm text-green-600';
                        }
                    });
            }, 500);
        }

        function checkEmail() {
            const email = document.getElementById('email').value.trim();
            const msg   = document.getElementById('email-msg');
            clearTimeout(emailTimer);
            if (!email) { msg.classList.add('hidden'); return; }
            emailTimer = setTimeout(() => {
                fetch('/api/check-email?email=' + encodeURIComponent(email))
                    .then(r => r.json())
                    .then(data => {
                        if (data.taken) {
                            msg.textContent = '✗ Există deja un cont cu această adresă';
                            msg.className = 'mt-1 text-sm text-red-500';
                        } else {
                            msg.textContent = '✓ Adresa este disponibilă';
                            msg.className = 'mt-1 text-sm text-green-600';
                        }
                    });
            }, 500);
        }

        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.querySelector('svg').style.opacity = isHidden ? '1' : '0.4';
        }

        function checkMatch() {
            const pass = document.getElementById('password').value;
            const conf = document.getElementById('password_confirmation').value;
            const msg  = document.getElementById('match-msg');
            if (!conf) { msg.classList.add('hidden'); return; }
            if (pass === conf) {
                msg.textContent = '✓ Parolele coincid';
                msg.className = 'mt-1 text-sm text-green-600';
            } else {
                msg.textContent = '✗ Parolele nu coincid';
                msg.className = 'mt-1 text-sm text-red-500';
            }
        }
    </script>
</x-guest-layout>
