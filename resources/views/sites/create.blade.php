<x-app-layout :title="__('Neue Seite')">

    <x-admin-container>
        <form method="POST" action="{{ route('sites.store') }}" class="max-w-xl flex flex-col gap-4">
            @csrf
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="url" :value="__('URL')" />
                <x-text-input id="url" class="block mt-1 w-full" type="url" name="url" :value="old('url')" required autofocus />
                <x-input-error :messages="$errors->get('url')" class="mt-2" />
            </div>
            <div>
                <div class="flex gap-2">
                    <input type="checkbox" name="status" id="status" value="live" checked>
                    <x-input-label for="status" :value="__('Live')" />
                </div>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>
            <div>
                <x-primary-button>
                    {{ __('Speichern') }}
                </x-primary-button>
            </div>
    </x-admin-container>

</x-app-layout>
