@extends('layouts.app')

@section('content')
    @if (is_array($emails))
        <div class="eventBlock">
            <div class="saveEmails">
                <h3> These email addresses unsaved. Select that you want to keep. </h3>
                <form action="/emails" method="post" id="emails">
                    {{ csrf_field() }}
                    @foreach($emails as $key => $value)
                    <div class="checkboxEmails">
                        <input type="checkbox" name="{{ $key }}" value="{{ $value }}" > <span> {{ $value }} </span>
                    </div>
                   @endforeach

                    <div class="save">
                        <button type='submit' name="save" value='true' class="buttonStyle" id="save"> Save Email</button>
                    </div>

                </form>
            </div>
        </div>
    @else
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
                    <button type="submit" name='send' id="send" value='true' class="buttonStyle"> Send</button>
                    <button type='submit' name='select' id='selectEmails' value='true' class="buttonStyle"> Select
                        Email
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection
