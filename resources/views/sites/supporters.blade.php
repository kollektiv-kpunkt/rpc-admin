<x-app-layout :title="__('Supporters for ') . $site->name">
    <x-button-bar>
        <a href="{{ route('sites.supporters.export', ["site" => $site->id]) }}" class="rpc-button">
            {{ __('Supporters exportieren') }}
        </a>
    </x-button-bar>
    <x-admin-container>
        @if ($supporters->isEmpty())
            <p class="text-xl font-bold">{{ __('Für diese Seite konnten keine Supporters gefunden werden.') }}</p>
        @else
        <table>
            <tr>
                <th>{{ __('UUID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('E-Mail Adresse') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Aktionen') }}</th>
            </tr>
            @foreach ($supporters as $supporter)
                <tr>
                    <td>{{ $supporter->uuid }}</td>
                    <td>{{ $supporter->name }}</td>
                    <td>{{ $supporter->email }}</td>
                    <td>{{ $supporter->status }}</td>
                    <td>
                        <form action="{{ route('supporters.destroy', [$supporter]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">{{ __('Löschen') }}</button>
                        </form>
                        @if ($supporter->status === 'pending')
                        <form action="{{ route('supporters.activate', [$supporter]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-green-500 hover:text-green-700">{{ __('Aktivieren') }}</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        @endif
    </x-admin-container>
</x-app-layout>
