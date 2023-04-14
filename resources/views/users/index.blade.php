<x-app-layout :title="__('Benutzer*innen')">

    <x-admin-container>
        @if ($users->isEmpty())
            <p class="text-xl font-bold">{{ __('Keine Benutzer*innen') }}</p>
        @else
        <table>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('E-Mail') }}</th>
                <th>{{ __('Rolle') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Aktionen') }}</th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ ($user->admin_activation || $user->role == "admin" ) ? "aktiv" : "inaktiv" }}</td>
                    <td>
                        <form action="{{ route('users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="underline text-red-600">{{ __('Löschen') }}</button>
                        </form>
                        @if (!$user->admin_activation && $user->role != "admin")
                        <form action="{{ route('users.activate', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="underline text-green-600">{{ __('Aktivieren') }}</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        @endif
    </x-admin-container>
</x-app-layout>

