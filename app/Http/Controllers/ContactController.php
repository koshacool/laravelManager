<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Http\Middleware\Sort;
use App\Http\Middleware\Pagination;
use Illuminate\Contracts\Validation\Validator;

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
        if (Auth::user()->name == 'Config') {
            $this->setDBDefaultValues();
            $this->addEmptyContact();
        }

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
//        return $contacts;
        return view('pages.showlist', ['contacts' => $contacts, 'sortValues' => $sortValues]);
    }

    public function record(Request $request, $id = 1)
    {


//return $request->best_phone;
        $contact = Contact::where('user_id', '=', ($id == 1) ? 1 : Auth::user()->id)
            ->with('phones')
            ->with('addresses')
            ->with('location')
            ->find($id);

        $contact->city = City::where('id', '=', $contact->location->city_id)->get();
        $contact->state = State::where('id', '=', $contact->location->state_id)->get();
        $contact->country = Country::where('id', '=', $contact->location->country_id)->get();
//return $contact;
        if ($request->isMethod('post')) {
            $validationRules = [
                'first' => 'required|min:3',
                'last' => 'required|min:3',
                'email' => 'required|email|newemail:' . $id,
                'home' => 'required|integer',
                'work' => 'required|integer',
                'cell' => 'required|integer',
                'address1' => 'required',
                'address2' => 'required',
                'city' => 'required',
                'zip' => 'required',
                'state' => 'required',
                'country' => 'required',
                'birthday' => 'required',

            ];

            $this->validate($request, $validationRules);
            $request->id = $id;

            if ($id == '1') {
                $this->addContact($request);
            } else {
                $this->editContact($request, $id);
            }
            return redirect('/showlist');
        }

        return view('pages.record', ['contact' => $contact]);
    }

    public function remove(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $contact = Contact::where('user_id', '=', Auth::user()->id)
                ->find($id);
            if ($contact) {
                $contact->addresses()->delete();
                $contact->phones()->delete();
                $contact->location()->delete();
                $contact->delete();
                return redirect('/showlist');
            }
        }

        return view('pages.confirm', ['id' => $id]);
    }

    public function emails(Request $request)
    {
        return view('pages.emails');
    }


    public function editContact($request, $id)
    {

        $contact = Contact::find($id);
        $contact->first = $request['first'];
        $contact->last = $request['last'];
        $contact->email = $request['email'];
        $contact->birthday = $request['birthday'];
        $contact->save();

        $addresses = $contact->addresses()->get();
        $address1 = $addresses[0];
        $address1->address = $request['address1'];
        $address1->address_type = 'address1';
        $address1->save();

        $address2 = $addresses[1];
        $address2->address = $request['address2'];
        $address2->address_type = 'address2';
        $address2->save();

        $phones = $contact->phones()->get();
        $home = $phones[0];
        $home->phone = $request['home'];
        $home->best_phone = ($request['best_phone'] == 'home') ? '1' : '0';
        $home->phone_type = 'home';
        $home->save();

        $work = $phones[1];
        $work->phone = $request['work'];
        $work->best_phone = ($request['best_phone'] == 'work') ? '1' : '0';
        $work->phone_type = 'work';
        $work->save();

        $cell = $phones[2];
        $cell->phone = $request['cell'];
        $cell->best_phone = ($request['best_phone'] == 'cell') ? '1' : '0';
        $cell->phone_type = 'cell';
        $cell->save();

        $state = State::firstOrCreate(['state' => $request['state']]);
        $country = Country::firstOrCreate(['country' => $request['country']]);

        $city = new City();
        $city = $city->firstOrCreate(['zip' => $request['zip'], 'city' => $request['city']]);

        $locations = $contact->location()->get();
        $location = $locations[0];
        $location->city_id = $city->id;
        $location->state_id = $state->id;
        $location->country_id = $country->id;
        $location->save();
    }

    public function addContact($request)
    {
        $contact = Contact::create([
            'id' => $request['id'],
            'first' => $request['first'],
            'email' => $request['email'],
            'last' => $request['last'],
            'user_id' => Auth::user()->id,
            'birthday' => $request['birthday'],
        ]);

        $address1 = Address::create([
            'address_type' => 'address1',
            'address' => $request['address1'],
            'contact_id' => $contact->id,
        ]);

        $address1 = Address::create([
            'address_type' => 'address2',
            'address' => $request['address2'],
            'contact_id' => $contact->id,
        ]);

        $work = Phone::create([
            'phone_type' => 'work',
            'phone' => $request['work'],
            'best_phone' => ($request['best_phone'] == 'work') ? '1' : '0',
            'contact_id' => $contact->id,
        ]);

        $home = Phone::create([
            'phone_type' => 'home',
            'phone' => $request['home'],
            'best_phone' => ($request['best_phone'] == 'home') ? '1' : '0',
            'contact_id' => $contact->id,
        ]);

        $cell = Phone::create([
            'phone_type' => 'cell',
            'phone' => $request['cell'],
            'best_phone' => ($request['best_phone'] == 'cell') ? '1' : '0',
            'contact_id' => $contact->id,
        ]);

        $state = State::firstOrCreate(['state' => $request['state']]);
        $country = Country::firstOrCreate(['country' => $request['country']]);

        $city = new City();
        $city->city = $request['city'];
        $city = $city->firstOrCreate(['zip' => $request['zip'], 'city' => $request['city']]);

        $location = Location::create([
            'city_id' => $city->id,
            'state_id' => $state->id,
            'country_id' => $country->id,
            'contact_id' => $contact->id,
        ]);
    }


    public function addEmptyContact()
    {
        $contact = new Contact();
        $contact->first = null;
        $contact->last = null;
        $contact->email = null;
        $contact->birthday = null;
        Auth::user()->contacts()->save($contact);

        $address1 = new Address();
        $address1->address = null;
        $address1->address_type = 'address1';
        $contact->addresses()->save($address1);

        $address2 = new Address();
        $address2->address = null;
        $address2->address_type = 'address2';
        $contact->addresses()->save($address2);

        $work = new Phone();
        $work->phone = null;
        $work->best_phone = 0;
        $work->phone_type = 'home';
        $contact->phones()->save($work);

        $home = new Phone();
        $home->phone = null;
        $home->best_phone = 0;
        $home->phone_type = 'work';
        $contact->phones()->save($home);

        $cell = new Phone();
        $cell->phone = null;
        $cell->best_phone = 1;
        $cell->phone_type = 'cell';
        $contact->phones()->save($cell);

        $state = State::firstOrCreate(['state' => null]);
        $country = Country::firstOrCreate(['country' => null]);

        $city = new City();
        $city->city = null;
        $city = $city->firstOrCreate(['zip' => null]);

        $location = new Location();
        $location->city_id = $city->id;
        $location->state_id = $state->id;
        $location->country_id = $country->id;
        $contact->location()->save($location);


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
