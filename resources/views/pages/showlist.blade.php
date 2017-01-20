@extends('layouts.app')

@section('content')
    @foreach ( $contacts  as $contact)
        <p>This is user {{ $contact->phone }}</p>
    @endforeach
@endsection
