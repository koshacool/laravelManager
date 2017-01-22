@extends('layouts.app')

@section('content')

    <div class="eventBlock">
        <div class="event">
            <h3> EVENT PAGE </h3>
            <span class='errorMessage' id="emailsMessage"> {{ $errors->first('emails') }} </span><br>
            <form action="/emails" method="post" id="emails">
                {{ csrf_field() }}
                <b> Email </b>
                <input type="text" name='emails' id="inputEmails" class="forValidation"
                       placeholder="enter email address" size="50"
                       value="{{ (old('emails') === null) ? $emails : old('emails') }}">
                <button type="submit" name='send' id="send" value='1' class="buttonStyle"> Send</button>
                <button type='submit' name='select' id='selectEmails' value='true' class="buttonStyle"> Select
                    Email
                </button>
            </form>
        </div>
    </div>


@endsection
