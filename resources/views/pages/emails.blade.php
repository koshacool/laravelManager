<?php if (empty($modelObject->getAdditionalData('newEmails'))): ?>
    <div class="eventBlock">
        <div class="event">
            <h3> EVENT PAGE </h3>
            <span class='errorMessage' id="emailsMessage"> <?= $modelObject->errorMessages['emails']; ?> </span><br>
            <form action="/contact/emails" method="post" id="emails">
                <b> Email </b>
                <input type="text" name='emails' id="inputEmails" class="forValidation" placeholder="enter email address" size="50" value="<?= $modelObject->getAdditionalData('emails') ?>" >
                <button type="submit" name='send' id="send" value='1' class="buttonStyle" > Send</button>
                <button type='submit' name='selectEmails' id='selectEmails' value='true' class="buttonStyle"> Select Email</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="eventBlock">
        <div class="saveEmails">
            <h3> These email addresses unsaved. Select that you want to keep. </h3>
            <form action="/contact/save" method="post" id="emails">
                <?php foreach ($modelObject->getAdditionalData('newEmails') as $key => $value): ?>
                    <div class="checkboxEmails">
                        <input type="checkbox" name=<?= $key ?> value=<?= $value ?> > <span><?= $value ?></span>
                    </div>
                <?php endforeach; ?>

                <div class="save">
                    <button type='submit' class="buttonStyle" id="saveEmails"> Save Email</button>
                </div>

            </form>
        </div>
    </div>
<?php endif; ?>