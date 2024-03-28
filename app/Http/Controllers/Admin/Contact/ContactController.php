<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Contact\StoreContactRequest;
use App\Models\Contact;
use App\Traits\AuthorizeCheck;
use App\Traits\CreateResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use AuthorizeCheck, CreateResponse;

    public function index()
    {
        $this->authorizeCheck('country view');
        try {
            $data = Contact::all();
            return $this->create_response(true,  'Mail Sent Successfully', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //store

    public function store(StoreContactRequest $request)
    {
        try {
            

            $valid = $request->validated();
            
            $data = Contact::create($valid);
            return $this->create_response(true,  'ok', $data, 201);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //show 

    public function show($lang, Contact $contact)
    {
        $this->authorizeCheck('country view');

        try {
            $data = $contact;
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //destroy

    public function destroy($lang, Contact $contact)
    {
        $this->authorizeCheck('country delete');
        try {
            $contact->delete();
            return $this->create_response(true,  'ok', $contact, 203);

            // return $this->create_response(false,  'not ok', $data, 422);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage(), null, 500);
        }
    }
}
