<div class="headerAuthorized">
    <a class="wise" href="#"></a>
    <div class="blockButtons" id="blockButtons" >
        <ul class="menu">
            <li>
                <div class="linkStyle">
                    <a href="/contact/showlist"><img src="/Public/Images/home.png"><span>Home</span></a>
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
            <span>Logged as:</span> <?= $userObject->getAttribute('login'); ?>
        </div>
        <div class="linkStyle">
            <a href="/contact/logout"><img src="/Public/Images/logoff.png"><span>Logout</span></a>
        </div>
    </div>
</div>