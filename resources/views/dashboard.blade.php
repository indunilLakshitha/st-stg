@section('title', 'Dashboard')
<x-app-layout>
    @if (in_array(Auth::user()->er_status, [4,3]))
        <livewire:dashboard />
    @endif
    </ x-app-layout>
