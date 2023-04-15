<x-app-layout :title="__('Supporters for ') . $site->name">
    <x-button-bar>
        <a href="{{ route('supporters.export', ["site" => $site->id]) }}" class="rpc-button">
            {{ __('Supporters exportieren') }}
        </a>
        <a href="{{ route('supporters.create', ["site" => $site->id]) }}" class="rpc-button indigo">
            {{ __('Supporter erstellen') }}
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
                        <form action="{{ route('supporters.destroy', [$site, $supporter]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">{{ __('Löschen') }}</button>
                        </form>
                        @if ($supporter->status != 'active')
                        <form action="{{ route('supporters.activate', [$site, $supporter]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-green-500 hover:text-green-700">{{ __('Aktivieren') }}</button>
                        </form>
                        @else
                        <form action="{{ route('supporters.deactivate', [$site, $supporter]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-orange-500 hover:text-orange-700">{{ __('Deaktivieren') }}</button>
                        </form>
                        @endif
                        <a href="{{ route('supporters.edit', [$site, $supporter]) }}" class="text-purple-500 hover:text-purple-900">Bearbeiten</a>
                    </td>
                </tr>
            @endforeach
        </table>
        @endif
    </x-admin-container>
</x-app-layout>
