<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Http\Middleware\Sort;
use App\Http\Middleware\Pagination;
use Illuminate\Contracts\Validation\Validator;
use Cookie;

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

    public function remove(Request $request, $id = null)
    {
        if (!$id) {
            return redirect('/showlist');
        }

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
        $emails = Cookie::get('cookieEmailsString'); //Get  data from COOKIE
        Cookie::queue(Cookie::forget('cookieEmailsString'));//Remove cookie
        $urlPath = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);//Save url path from which a visitor came to value

        //If user doesn't come from EventContacts.php set empty COOKIE with emails
        if (!preg_match("/^\/select$/", $urlPath)) {
            $emails = null;
        }

        if ($request->isMethod('post')) {
            //If user push 'Select email' save data to COOKIE and redirect to EventContacts page
            if ($request->select) {
                Cookie::queue('cookieEmailsString', $request->emails);
                return redirect("/select");
                exit();
            }

            if ($request->send) {
                $this->validate($request, ['emails' => 'required|emails']);
                $emails = explode(',', $request->emails);
                $emails = $this->findNewEmails($emails);
                if (empty($emails)) {
                    return redirect("/showlist");
                    exit();
                }
            }


            if ($request->save) {
                $allInputs = $request->input();
                foreach ($allInputs as $key => $value) {
                    if (is_numeric($key)) {
                        $this->addEmptyContact($value);
                    }
                }
                return redirect("/showlist");
                exit();
            }
        }
        return view('pages.emails', ['emails' => $emails]);
    }

    public function select(Request $request)
    {
        $arrSelectedEmails = null;
        $emails = null;
        $checkboxes = null;

        //Save url path from which a visitor came
        $urlPath = null;
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $urlPath = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);//Save url path from which a visitor came to value
        }

        //If user doesn't come from /select - set empty COOKIE with emails
        if (!preg_match("/^\/select$/", $urlPath)) {
            //Save data from COOKIE to array
            $emailsString = Cookie::get('cookieEmailsString');
        }

        //Save entered emails from page send Emails
        if (!empty($emailsString)) {
            //convert input string with emails to array
            $arraySaveEmails = explode(',', $emailsString);


            //Remove whitespaces in values
            foreach ($arraySaveEmails as $key => $value) {
                $arraySaveEmails[$key] = trim($value);
            }
            $emailsString = null;

            //Remove from array saved emails
            foreach ($arraySaveEmails as $key => $value) {
                $contacts = Contact::where('user_id', '=', Auth::user()->id)
                    ->where('email', '=', $value)
                    ->get();

                foreach ($contacts as $contact) {

//                    if ($contact->email == $value) {
                    $arrSelectedEmails[$contact->id] = $value;
                    unset($arraySaveEmails[$key]);
//                    }
                }
            }


            //Save emails from array to string value
            foreach ($arraySaveEmails as $key => $value) {
                if (empty($emails)) {
                    $emails = $value;
                } else {
                    $emails .= ',' . $value;
                }
            }
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

        if ($request->isMethod('post')) {
            $arrSelectedEmails = Cookie::get('arrSelectedEmails');
            $lastShowContacts = Cookie::get('contacts');
//            return $lastShowContacts;


            $allInputs = $request->input();
            foreach ($allInputs as $key => $value) {
                if (is_numeric($key)) {
                    $checkboxes[$key] = $value;
                }
            }
            if ($request->selectAll) {
                $checkboxes['selectAll'] = $request->selectAll;
            }

            list($arrSelectedEmails) = $this->saveSelect($checkboxes, $arrSelectedEmails, $lastShowContacts);//Check which emails from last page save to array


        }

        foreach ($contacts as $contact) {
            $arrayForTestSelect[$contact->id] = $contact->email;
        }

        $selectAll = $this->checkPushSelectAll($arrSelectedEmails, $arrayForTestSelect);//Check to display button 'selectAll' selected
        foreach ($contacts as $contact) {
            $arrayForTestSelect[$contact->id] = $contact->email;
        }
        $selectAll = $this->checkPushSelectAll($arrSelectedEmails, $arrayForTestSelect);//Check to display button 'selectAll' selected


        Cookie::queue('contacts', $arrayForTestSelect);
//        return Cookie::get('contacts');
        Cookie::queue('emails', $emails);
        Cookie::queue('arrSelectedEmails', $arrSelectedEmails);


        if ($request->accept) {
            $emails = Cookie::get('emails');
            foreach ($arrSelectedEmails as $key => $value) {
                if (empty($emails)) {
                    $emails = $value;
                } else {
                    $emails .= ',' . $value;
                }
            }
            Cookie::queue('cookieEmailsString', $emails);
            return redirect("/emails");
            exit();

        }

        return view('pages.select', [
            'contacts' => $contacts,
            'sortValues' => $sortValues,
            'selectAll' => $selectAll,
            'selected' => $arrSelectedEmails,
        ]);
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

    public function addEmptyContact($email = null)
    {
        $contact = new Contact();
        $contact->first = null;
        $contact->last = null;
        $contact->email = $email;
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

    /**
     * Save selected data on page to array
     *
     * @param array $arrayPostValues Array with data recieved from POST
     * @param array $arrSelectSave Array with data selected on page earlier
     * @param array $arrDateShowOnPage Array with data displayed on the page
     * @return array
     */
    public function saveSelect($arrayPostValues, $arrSelectSave, $arrDateShowOnPage)
    {
        $displayedEmails = 0;//number displayed data on page
        $savedEmails = 0;//number saved data
        $selectedEmails = 0;//number selected data on page

        //count all shown data($displayedEmails), count the saved data, count selected data($selectedEmails)
        foreach ($arrDateShowOnPage as $key => $value) {
            $displayedEmails++;
            if (isset($arrSelectSave[$key])) {
                $savedEmails++;
            }
            if (isset($arrayPostValues[$key])) {
                $selectedEmails++;
            }
        }

        //Check select all if exist checkbox selectAll
        if (isset($arrayPostValues['selectAll'])) {

            if ($displayedEmails == $selectedEmails) {//Save all emails which displayed in page
                foreach ($arrDateShowOnPage as $key => $value) {
                    $arrSelectSave[$key] = $value;
                }
            } else {//When didn't select all emails
                foreach ($arrDateShowOnPage as $key => $value) {
                    if ($displayedEmails == $savedEmails) {
                        if (!isset($arrayPostValues[$key])) {//Remove from saved emails not selected emails
                            if (isset($arrSelectSave[$key])) {
                                unset($arrSelectSave[$key]);
                            }
                        } else {//Save selected emails
                            $arrSelectSave[$key] = $value;
                        }
                    } else {
                        $arrSelectSave[$key] = $value;//save all shown rows
                    }
                }
            }

        } else {//Check select emails if not exist checkbox selectAll
            if ($displayedEmails == $selectedEmails && $displayedEmails == $savedEmails) {//Remove all shown emails
                foreach ($arrDateShowOnPage as $key => $value) {
                    if (isset($arrSelectSave[$key])) {
                        unset($arrSelectSave[$key]);
                    }
                }
            } elseif ($displayedEmails != $selectedEmails && $displayedEmails == $savedEmails) {//Remove all non-selected emails
                foreach ($arrDateShowOnPage as $key => $value) {
                    if (isset($arrSelect[$key])) {
                        $arrSelectSave[$key] = $value;
                    } else {
                        if (isset($arrSelectSave[$key])) {
                            unset($arrSelectSave[$key]);
                        }
                    }
                }
            } elseif ($displayedEmails == $selectedEmails && $displayedEmails != $savedEmails) {//save all shown emails
                foreach ($arrDateShowOnPage as $key => $value) {
                    $arrSelectSave[$key] = $value;
                }
            } else {//save only select rows
                foreach ($arrDateShowOnPage as $key => $value) {
                    if (isset($arrayPostValues[$key])) {
                        $arrSelectSave[$key] = $value;
                    } else {
                        if (isset($arrSelectSave[$key])) {
                            unset($arrSelectSave[$key]);
                        }
                    }

                }
            }
        }
        return array($arrSelectSave);
    }

    /**
     * Check to show checkbox 'Select All' selected or not selected on page EventContacts
     *
     * @param array $arrSelectSave Array with data selected on page earlier
     * @param array $arrDateShowOnPage Array with data displayed on the page
     * @return string
     */
    public function checkPushSelectAll($arrSelectSave, $arrDateShowOnPage)
    {
        $countRows = 0;//number displayed data on page
        $countSave = 0; //number saved data

        //count all shown data($countRows), count the saved data($countSave)
        foreach ($arrDateShowOnPage as $key => $value) {
            $countRows++;
            if (isset($arrSelectSave[$key])) {
                $countSave++;
            }
        }
        //check to select button 'SelectAll'
        if ($countRows == $countSave) {
            $selectAll = 'checked';
        } else {
            $selectAll = '';
        }
        return $selectAll;
    }

    /**
     *If email exist in database, remove it from array
     *
     * @param $connect Connect to DataBase
     * @param integer $user_id User's id, who add contact
     * @param array $arrayEmails Array with emails
     * @return array
     */
    public function findNewEmails(array $arrayEmails)
    {

        //Remove from array saved emails
        foreach ($arrayEmails as $key => $value) {
            $value = trim($value);
            $contacts = Contact::where('user_id', '=', Auth::user()->id)
                ->where('email', '=', $value)
                ->get();

            foreach ($contacts as $contact) {
                if ($contact->email == $value) {
                    unset($arrayEmails[$key]);
                }
            }
        }
        return $arrayEmails;
    }

}
