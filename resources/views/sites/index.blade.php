<x-app-layout :title="__('Seiten')">

    <x-button-bar>
        <a href="{{ route('sites.create') }}" class="rpc-button">
            {{ __('Neue Seite') }}
        </a>
    </x-button-bar>

    @if ($sites->isEmpty())
        <p class="text-xl font-bold">{{ __('Es wurden noch keine Seiten angelegt.') }}</p>
    @else
    <table>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('URL') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Aktionen') }}</th>
        </tr>
        @foreach ($sites as $site)
            <tr>
                <td>{{ $site->name }}</td>
                <td>{{ $site->url }}</td>
                <td>{{ $site->status }}</td>
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
</x-app-layout>
