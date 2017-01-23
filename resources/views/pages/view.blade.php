@extends('layouts.app')

@section('content')

    <div class="contactForm">
        <div class="blockView">
            <h2 align='center'> Information </h2>

            <div class="blockValue">
                <p class='valueName'> Last: </p>
                <p> <?= $contact->last ?> </p>
            </div>

            <div class="blockValue">
                <p class='valueName'> First: </p>
                <p> <?= $contact->first ?> </p>
            </div>

            <div class="blockValue">
                <p class='valueName'> Email: </p>
                <p> <?= $contact->email ?> </p>
            </div>

            @foreach ($contact->phones as $phone)
                <div class="blockValue">
                    <p class='valueName'> {{ucfirst($phone->phone_type) . ':'}} </p>
                    <p> <?= $phone->phone ?> </p>
                </div>
            @endforeach

            @foreach ($contact->addresses as $address)
                <div class="blockValue">
                    <p class='valueName'> {{ucfirst($address->address_type) . ':'}} </p>
                    <p> <?= $address->address ?> </p>
                </div>
            @endforeach

            @foreach ($contact->city as $city)
                <div class="blockValue">
                    <p class='valueName'> City: </p>
                    <p> <?= $city->city ?> </p>
                </div>
            @endforeach

            @foreach ($contact->state as $state)
                <div class="blockValue">
                    <p class='valueName'> State: </p>
                    <p> <?= $state->state ?> </p>
                </div>
            @endforeach

            @foreach ($contact->city as $city)
                <div class="blockValue">
                    <p class='valueName'> Zip: </p>
                    <p> <?= $city->zip ?> </p>
                </div>
            @endforeach

            @foreach ($contact->country as $country)
                <div class="blockValue">
                    <p class='valueName'> Country: </p>
                    <p> <?= $country->country ?> </p>
                </div>
            @endforeach

            <div class="blockValue">
                <p class='valueName'> Birthday: </p>
                <p> <?= $contact->birthday ?> </p>
            </div>


            <div class="linkStyle">
                <a href="/showlist">
                    <span>Ok</span>
                </a>
            </div>

        </div>
    </div>

@endsection