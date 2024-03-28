<?php

namespace App\Http\Controllers\Admin\Education;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Education\StoreEduRequest;
use App\Http\Requests\Admin\Education\UpdateEduRequest;
use App\Models\Education;
use App\Traits\AuthorizeCheck;
use App\Traits\CreateResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EduController extends Controller
{
    use AuthorizeCheck, CreateResponse;

    public function index()
    {
        $this->authorizeCheck('education view');
        try {
            $data = Education::all();
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //store

    public function store(StoreEduRequest $request)
    {
        try {
            $id = Auth::user();

            $valid = $request->validated();
            $valid['user_id'] = $id->id;
            
            $data = Education::create($valid);
            return $this->create_response(true,  'ok', $data, 201);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //show 

    public function show($lang, Education $education)
    {
        $this->authorizeCheck('education view');

        try {
            $data = $education;
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }
    //update

    public function update($lang, UpdateEduRequest $request, Education $education)
    {
        try {

            $valid = $request->validated();
            
            $education->update($valid);
            $data = Education::findOrFail($education->id);
            return $this->create_response(true,  'ok', $data, 202);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //destroy

    public function destroy($lang, Education $education)
    {
        $this->authorizeCheck('education delete');
        try {
            $education->delete();
            return $this->create_response(true,  'ok', $education, 203);

            // return $this->create_response(false,  'not ok', $data, 422);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage(), null, 500);
        }
    }
}
