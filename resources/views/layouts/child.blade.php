@extends('layouts.app')

@section('title', $data['title'] ?? 'Child Page')

@section('sidebar')
    @parent
    <h5>This is appended to the master sidebar</h5>
@endsection

{{-- @vardump($data) --}}

@section('content')
    <h3>This is my body content</h3>
    <p>{{ $data['content'] }}</p>
    <div>{!! $data['html'] !!}</div>

    @forelse ($data['users'] ?? [] as $user)
        @if($loop->first)
            <p>1st Row => {{ $user->name }}</p>
        @endif
        <li>{{ $user->name }}</li>
        <li>{{ $user->age }}</li>
        @if($loop->first)
            <p>Last Row => {{ $user->name }}</p>
        @endif
    @empty
        <p>No users</p>
    @endforelse
    @form(action="/users", method="delete", style="/styles/style.css", class="red")
        <b>Testing</b>
    @endform

    @php $message = 'This is error message.'; @endphp
    <x-alert type="error" :message="$message" />

    <x-alert type="error" :message="$message">
        <b>This is slot</b>
    </x-alert>
@endsection
