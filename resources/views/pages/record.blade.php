@extends('layouts.app')

@section('content')
    <p>{{$contact->phones}}</p>
    @foreach($contact as $value)
        <p>{{$value}}</p>
    @endforeach
@endsection