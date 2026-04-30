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
                       oninput="checkMatch()"
                       class="block w-full pr-10 py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <button type="button" onclick="togglePassword('password', this)"
                        style="position:absolute; top:50%; right:0.625rem; transform:translateY(-50%); background:none; border:none; padding:0; cursor:pointer; color:#9ca3af; line-height:0;">
                    <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <p class="mt-1 text-xs text-gray-400">Minim 8 caractere.</p>
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
                    <svg id="eye-icon-password_confirmation" xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        let phoneTaken = false;

        function normalizePhone(phone) {
            let clean = phone.replace(/[\s\-\.\(\)]/g, '');
            clean = clean.replace(/^(\+40|0040)/, '0');
            return clean;
        }

        function checkPhone() {
            const phone = document.getElementById('phone').value.trim();
            const msg   = document.getElementById('phone-msg');
            clearTimeout(phoneTimer);
            phoneTaken = false;
            if (!phone) { msg.classList.add('hidden'); return; }
            // Validare format: accepta 07xx, 02xx, 03xx, +40..., 004...
            const phoneRegex = /^(\+40|0040|0)[0-9]{8,9}$/;
            const clean = normalizePhone(phone);
            if (!phoneRegex.test(phone.replace(/[\s\-\.\(\)]/g, ''))) {
                msg.textContent = '✗ Număr de telefon invalid (ex: 07xx xxx xxx)';
                msg.className = 'mt-1 text-sm text-red-500';
                return;
            }
            phoneTimer = setTimeout(() => {
                fetch('/api/check-phone?phone=' + encodeURIComponent(clean))
                    .then(r => r.json())
                    .then(data => {
                        phoneTaken = data.taken;
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

        let emailTaken = false;

        function checkEmail() {
            const email = document.getElementById('email').value.trim();
            const msg   = document.getElementById('email-msg');
            clearTimeout(emailTimer);
            emailTaken = false;
            if (!email) { msg.classList.add('hidden'); return; }
            // Validare format email inainte de request
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
            if (!emailRegex.test(email)) {
                msg.textContent = '✗ Adresă email invalidă';
                msg.className = 'mt-1 text-sm text-red-500';
                return;
            }
            emailTimer = setTimeout(() => {
                fetch('/api/check-email?email=' + encodeURIComponent(email))
                    .then(r => r.json())
                    .then(data => {
                        emailTaken = data.taken;
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
            const svg = btn.querySelector('svg');
            if (isHidden) {
                // ochi tăiat (parola vizibilă)
                svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                // ochi deschis (parola ascunsă)
                svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        function checkMatch() {
            const pass = document.getElementById('password').value;
            const conf = document.getElementById('password_confirmation').value;
            const msg  = document.getElementById('match-msg');
            if (!conf && !pass) { msg.classList.add('hidden'); return; }
            if (!conf) { msg.classList.add('hidden'); return; }
            if (pass === conf) {
                msg.textContent = '✓ Parolele coincid';
                msg.className = 'mt-1 text-sm text-green-600';
            } else {
                msg.textContent = '✗ Parolele nu coincid';
                msg.className = 'mt-1 text-sm text-red-500';
            }
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const pass = document.getElementById('password').value;
            const conf = document.getElementById('password_confirmation').value;
            if (pass !== conf) {
                e.preventDefault();
                const msg = document.getElementById('match-msg');
                msg.textContent = '✗ Parolele nu coincid';
                msg.className = 'mt-1 text-sm text-red-500';
                document.getElementById('password_confirmation').focus();
                return;
            }
            if (emailTaken) {
                e.preventDefault();
                const msg = document.getElementById('email-msg');
                msg.textContent = '✗ Există deja un cont cu această adresă';
                msg.className = 'mt-1 text-sm text-red-500';
                document.getElementById('email').focus();
                return;
            }
            if (phoneTaken) {
                e.preventDefault();
                const msg = document.getElementById('phone-msg');
                msg.textContent = '✗ Există deja un cont cu acest număr';
                msg.className = 'mt-1 text-sm text-red-500';
                document.getElementById('phone').focus();
            }
        });
    </script>
</x-guest-layout>
