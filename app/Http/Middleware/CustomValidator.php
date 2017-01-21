<?php
namespace App\Http\Middleware;

use Illuminate\Validation\Validator;
use App\Contact;
use Auth;

class CustomValidator extends Validator
{

    public function validateNewemail($attribute, $value, $parameters)
    {
        //$atribute - это название поля, в нашем случае site
        //$value - значение поля
        //$parameters - это параметры, которые можно передать так urlrl:ru, ($parameters=['ru'])
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
}