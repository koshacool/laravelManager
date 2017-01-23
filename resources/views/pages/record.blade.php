@extends('layouts.app')

@section('content')

    <div class="contactForm">
        <form name='formRecord' id='formRecord' method='post' class='regForm'
              action='/record/{{ $contact->id }}'>
            {{ csrf_field() }}

            <span class='errorMessage' id="firstMessage"> {{ $errors->first('first') }} </span>
            <div class="blockAddForm">
                <label class="fieldName" for="first"> First </label>
                <input type='text' id='first' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation
                        ' name="first" value='{{ (old('first') === null) ? $contact->first : old('first') }}'>
            </div>

            <span class='errorMessage' id="lastMessage"> {{ $errors->first('last') }} </span>
            <div class="blockAddForm">
                <label class="fieldName" for="last"> Last </label>
                <input type='text' id='first' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation
                        ' name="last" value='{{ (old('last') === null) ? $contact->last : old('last') }}'>
            </div>

            <span class='errorMessage' id="emailMessage"> {{ $errors->first('email') }} </span>
            <div class="blockAddForm">
                <label class="fieldName" for="email"> Email </label>
                <input type='text' id='first' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation
                        ' name="email" value='{{ (old('email') === null) ? $contact->email : old('email') }}'>
            </div>

            @foreach ($contact->phones as $phone)
                <span class='errorMessage' id="homeMessage"> {{ $errors->first($phone->phone_type) }} </span>
                <div class="blockAddForm">
                    <label class="fieldName" for="{{$phone->phone_type}}"> {{ ucfirst ($phone->phone_type) }} </label>
                    @if((old('best_phone') === null) ? $best_phone = $phone->best_phone : $best_phone = (old('best_phone') == $phone->phone_type) ? '1' : '0')
                        <input type='radio' name='best_phone' class='radio' checked
                               id="{{$phone->phone_type}}" value="{{$phone->phone_type}}">
                    @else
                        <input type='radio' name='best_phone' class='radio'
                               id="{{$phone->phone_type}}" value="{{$phone->phone_type}}">
                    @endif
                    <input type='text' id='first' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation
                        ' name="{{$phone->phone_type}}" value="{{ (old($phone->phone_type) === null) ? $phone->phone : old($phone->phone_type) }}">
                </div>
            @endforeach

            @foreach ($contact->addresses as $address)
                <span class='errorMessage' id="homeMessage"> {{ $errors->first($address->address_type) }} </span>
                <div class="blockAddForm">
                    <label class="fieldName" for="{{$address->address_type}}"> {{ucfirst($address->address_type)}} </label>
                    <input type='text' id='first' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation
                        ' name="{{$address->address_type}}" value='{{ (old($address->address_type) === null) ? $address->address : old($address->address_type) }}'>
                </div>
            @endforeach

            @foreach ($contact->city as $city)
                <span class='errorMessage' id="emailMessage"> {{ $errors->first('city') }} </span>
                <div class="blockAddForm">
                    <label class="fieldName" for="City"> City </label>
                    <input type='text' id='first' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation
                        ' name="city" value='{{ (old('city') === null) ? $city->city : old('city') }}'>
                </div>
            @endforeach

            @foreach ($contact->state as $state)
                <span class='errorMessage' id="emailMessage"> {{ $errors->first('state') }} </span>
                <div class="blockAddForm">
                    <label class="fieldName" for="City"> State </label>
                    <input type='text' id='first' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation
                        ' name="state" value="{{ (old('state') === null) ? $state->state : old('state') }}" >
                </div>
            @endforeach

            @foreach ($contact->city as $city)
                <span class='errorMessage' id="emailMessage"> {{ $errors->first('zip') }} </span>
                <div class="blockAddForm">
                    <label class="fieldName" for="zity"> Zip </label>
                    <input type='text' id='first' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation
                        ' name="zip" value='{{ (old('zip') === null) ? $city->zip : old('zip') }}'>
                </div>
            @endforeach

            @foreach ($contact->country as $country)
                <span class='errorMessage' id="emailMessage"> {{ $errors->first('country') }} </span>
                <div class="blockAddForm">
                    <label class="fieldName" for="country"> Country </label>
                    <input type='text' id='first' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation
                        ' name="country" value='{{ (old('country') === null) ? $country->country : old('country') }}'>
                </div>
            @endforeach

            <span class='errorMessage' id="birthdayMessage"> {{ $errors->first('birthday') }} </span>
            <div class="blockAddForm">
                <label class="fieldName" for="birthday"> Birthday </label>
                <input type='text' id='first' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation
                        ' name="birthday" value='{{ (old('birthday') === null) ? $contact->birthday : old('birthday') }}'>
            </div>


            <button class="buttonStyle" type="submit">
                <img src="/images/login.png">
                <span>Done</span>
            </button>


        </form>
    </div>
@endsection