<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title> Contact Manager </title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/css/ManagerStyle.css">
</head>
<body>


<!-- Authentication Links -->
@if (Auth::guest())
    <div class="headerNotAuthorized">
        <a class="wise" href="#"></a>
        <div class="blockButtons">
            <div class="linkStyle">
                <a class="authorisationButton" href="{{ url('/login') }}">
                    <img src="/images/authorisation.png">
                    <span>Authorisation</span>
                </a>
            </div>
            <div class="linkStyle">
                <a class="registrationButton" href="{{ url('/register') }}">
                    <img class="regImg" src="/images/registration.png">
                    <span>Registration</span>
                </a>
            </div>
        </div>
    </div>
@else
    <div class="headerAuthorized">
        <a class="wise" href="#"></a>
        <div class="blockButtons" id="blockButtons">
            <ul class="menu">
                <li>
                    <div class="linkStyle">
                        <a href="/contact/showlist"><img src="images/home.png"><span>Home</span></a>
                    </div>
                    <ul class="submenu">
                        <li>
                            <div class="linkStyle">
                                <a href="/contact/record"><span>Add</span></a>
                            </div>
                        </li>
                        <li>
                            <div class="linkStyle">
                                <a href="/contact/emails"><span>Event</span></a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="logged">
                {{ Auth::user()->name }}
            </div>
            <div class="linkStyle">

                <a href="{{ url('/logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <img src="images/logoff.png">
                    <span>Logout</span>
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
@endif


@yield('content')


<!-- Scripts -->
<script src="/js/app.js"></script>


<div class="copyright ">
    <span>©2007 Wise Engineering</span>
</div>

<!-- Scripts -->
{{--<script src="/Public/Js/BaseScript.js"></script>--}}
</body>
</html>
