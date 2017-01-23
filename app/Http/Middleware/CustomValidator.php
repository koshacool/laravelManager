<?php
namespace App\Http\Middleware;

use Illuminate\Validation\Validator;
use App\Contact;
use Auth;

class CustomValidator extends Validator
{

    public function validateNewemail($attribute, $value, $parameters)
    {
        //$atribute - field name
        //$value - field's value
        //$parameters - parameters
        $contacts = Contact::where('email', '=', $value)
            ->where('user_id', '=', Auth::user()->id)
            ->get();

        if (!empty($contacts)) {
            foreach ($contacts as $contact) {
                if ($contact->id != $parameters[0]) {
                    return false;
                }
            }
        }


        return true;
    }

    public function validateEmails($attribute, $value, $parameters)
    {
        $emails = explode(',', $value);

        foreach ($emails as $email) {
            $email = trim($email);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }


        return true;
    }
}