<x-guest-layout>
    <div class="w-full">
        <div class="mb-6 text-center">
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">
                Sistem Inventori SAMSAT
            </h1>
            <p class="mt-2 text-sm sm:text-base text-gray-600">Daftarkan akun baru Anda untuk mulai mengelola inventori.</p>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl px-5 sm:px-8 py-6">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" value="Nama Lengkap" />
                    <x-text-input
                        id="name"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" value="Alamat Email" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autocomplete="username"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Dropdown Role -->
                <div>
                    <x-input-label for="role" value="Hak Akses / Role" />
                    <select
                        id="role"
                        name="role"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >
                        <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas Gudang / Loket</option>
                        <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" value="Password" />
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                    <x-text-input
                        id="password_confirmation"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center w-full rounded-lg bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
                    >
                        Daftar Akun Baru
                    </button>

                    <div class="mt-4 text-center">
                        <a
                            class="text-sm text-gray-600 hover:text-gray-900 underline"
                            href="{{ route('login') }}"
                        >
                            Sudah punya akun? Login di sini
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

