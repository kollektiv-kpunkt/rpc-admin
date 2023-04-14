<?php
$site = \App\Models\Site::where('id', request()->site)->first();
dd($site);
?>
<x-app-layout :title="__('Dashboard')">
    <x-admin-container>
        {{ __("You're logged in!") }}
    </x-admin-container>
</x-app-layout>
