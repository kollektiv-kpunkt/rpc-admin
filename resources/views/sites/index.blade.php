<x-app-layout :title="__('Seiten')">

    <x-button-bar>
        <a href="{{ route('sites.create') }}" class="rpc-button">
            {{ __('Neue Seite') }}
        </a>
    </x-button-bar>
    <x-admin-container>
        @if ($sites->isEmpty())
            <p class="text-xl font-bold">{{ __('Es wurden noch keine Seiten angelegt.') }}</p>
        @else
        <table>
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('URL') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Key') }}</th>
                <th>{{ __('Aktionen') }}</th>
            </tr>
            @foreach ($sites as $site)
                <tr>
                    <td>{{ $site->id }}</td>
                    <td>{{ $site->name }}</td>
                    <td>{{ $site->url }}</td>
                    <td>{{ $site->status }}</td>
                    <td style="max-width: 150px;"><input type="password" name="" id="" value="{{$site->key}}" class="bg-gray-300 border-none px-2"><em class="block underline" onclick="showKey(event,this)">Show</em></td>
                    <td>
                        <a href="{{ route('sites.edit', $site) }}" class="underline">{{ __('Bearbeiten') }}</a>
                        <form action="{{ route('sites.destroy', $site) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="underline text-red-600">{{ __('Löschen') }}</button>
                        </form>
                        <a href="{{ route('sites.supporters', $site) }}" class="underline text-green-500">{{ __('Supporters') }}</a>
                    </td>
                </tr>
            @endforeach
        </table>
        @endif
        <script>
            function showKey(e,el){
            e = e || window.event;
            let input = e.target.previousElementSibling;
                if(input.type == 'password'){
                    input.type = 'text';
                    el.innerHTML = 'Hide';
                }else{
                    input.type = 'password';
                    el.innerHTML = 'Show';
                }
            }
        </script>
    </x-admin-container>
</x-app-layout>

