<x-app-layout :title="__('Supporter bearbeiten')">

    <x-admin-container>
        <form method="POST" action="{{ route('supporters.update', ["site" => $site, "supporter" => $supporter]) }}" class="max-w-xl flex flex-col gap-4 mx-auto">
            @csrf
            @method('PUT')
            <div class="opacity-50">
                <x-input-label for="uuid" :value="__('UUID')" />
                <x-text-input id="uuid" class="block mt-1 w-full" type="text" name="uuid" :value="$supporter->uuid" readonly required autofocus />
                <x-input-error :messages="$errors->get('uuid')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$supporter->name" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="email" :value="__('E-Mail Adresse')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="$supporter->email" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <hr class="border-0 border-t-2 border-t-gray-300 my-4"/>
            @foreach ($customFields as $field)
                @if ($field == 'uuid' || $field == 'name' || $field == 'email' || $field == "__")
                    <input type="hidden" name="data[{{$field}}]" value="{{$supporter->data[$field] ?? ''}}">
                @else
                <div>
                    <x-input-label for="data[{{$field}}]" :value="__('Benutzerdefiniertes Feld: ') . $field" />
                    <x-text-input id="data[{{$field}}]" class="block mt-1 w-full" type="text" name="data[{{$field}}]" :value="$supporter->data[$field] ?? ''" autofocus />
                    <x-input-error :messages="$errors->get('data')" class="mt-2" />
                </div>
                @endif
            @endforeach
            <x-supporter.add-custom-field />
            <div>
                <x-primary-button>
                    {{ __('Speichern') }}
                </x-primary-button>
            </div>
    </x-admin-container>

</x-app-layout>
