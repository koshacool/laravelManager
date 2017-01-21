<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Http\Middleware\Sort;
use App\Http\Middleware\Pagination;

use App\Contact;
use App\Phone;
use App\PhoneType;
use App\City;
use App\Address;
use App\AddressType;
use App\Country;
use App\State;
use App\Location;


class ContactController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the contact list
     *
     * @return \Illuminate\Http\Response
     */
    public function showlist(Request $request)
    {

        $sorting = new Sort();
        $sortValues = $sorting->sortTable($request->all());

        $contacts = Contact::with(['phones' => function ($query) {
            $query->where('best_phone', '=', '1');
        }])
            ->where('user_id', '=', Auth::user()->id)
            ->orderBy($sortValues['mainSortColumn'], $sortValues['sortDirectionMainColumn'])
            ->orderBy($sortValues['secondarySortColumn'], $sortValues['sortDirectionSecondaryColumn'])
            ->paginate(ROWS_ON_PAGE);


        $sortValues['page'] = $contacts->currentPage();
        $sortValues['offset'] = $contacts->firstItem();
        $pagination = new Pagination();
        $firstLastPages = $pagination->pagination($contacts->currentPage(), $contacts->lastPage());
        $sortValues = array_merge($firstLastPages, $sortValues);

        return view('pages.showlist', ['contacts' => $contacts, 'sortValues' => $sortValues]);
    }

    public function record(Request $request, $id = 1)
    {
//        return $id;
//        return Auth::user()->contacts()->phones()->find(1);
////            ->with('phones')
////            ->with('addresses')
////            ->with('city')
////            ->find($id);


        return $contact = Contact::with('phones')
            ->with('addresses')
            ->with('city')
            ->find($id);


//        return $contact->location;
        return view('pages.record', ['contact' => $contact]);
    }

    public function addContact()
    {
        $contact = new Contact();
        $contact->first = null;
        $contact->last = null;
        $contact->email = 'null@i.ua';
        $contact->birthday = null;
        Auth::user()->contacts()->save($contact);

        $address1 = new Address();
        $address1->address = null;
        $address1->address_type_id = 1;
        $contact->addresses()->save($address1);

        $address2 = new Address();
        $address2->address = null;
        $address2->address_type_id = 2;
        $contact->addresses()->save($address2);

        $work = new Phone();
        $work->phone = null;
        $work->best_phone = 0;
        $work->phone_type_id = 1;
        $contact->phones()->save($work);

        $home = new Phone();
        $home->phone = null;
        $home->best_phone = 0;
        $home->phone_type_id = 2;
        $contact->phones()->save($home);

        $cell = new Phone();
        $cell->phone = null;
        $cell->best_phone = 1;
        $cell->phone_type_id = 3;
        $contact->phones()->save($cell);

        $state = State::firstOrCreate(['state' => null]);
        $country = Country::firstOrCreate(['country' => null]);
        $city = City::firstOrCreate(['zip' => null, 'city' => null]);

        $location = new Location();
        $location->city_id = $city->id;
        $location->state_id = $state->id;
        $location->country_id = $country->id;
        $contact->location()->save($location);


    }

    public function removeContact($id)
    {
        $contact = Contact::find($id);
        $contact->addresses()->delete();
        $contact->phones()->delete();
        $contact->location()->delete();
        $contact->delete();

    }

    private function setDBDefaultValues()
    {

        $phoneType1 = new PhoneType;
        $phoneType2 = new PhoneType;
        $phoneType3 = new PhoneType;
        $phoneType1->type = 'home';
        $phoneType2->type = 'work';
        $phoneType3->type = 'cell';
        $phoneType1->save();
        $phoneType2->save();
        $phoneType3->save();

        $address1 = new AddressType;
        $address2 = new AddressType;
        $address1->type = 'address1';
        $address2->type = 'address2';
        $address1->save();
        $address2->save();

        $city = new City;
        $city->zip = null;
        $city->city = null;
        $city->save();

        $state = new State;
        $state->state = null;
        $state->save();

        $country = new Country;
        $country->country = null;
        $country->save();

    }
}
