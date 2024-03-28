<?php

namespace App\Http\Controllers\Admin\Job;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Job\StoreJobRequest;
use App\Http\Requests\Admin\Job\UpdateJobRequest;
use App\Models\Job;
use App\Traits\AuthorizeCheck;
use App\Traits\CreateResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    use AuthorizeCheck, CreateResponse;

    public function index()
    {
        $this->authorizeCheck('job view');
        try {
            $data = Job::all();
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //store

    public function store(StoreJobRequest $request)
    {
        try {
            $id = Auth::user();

            $valid = $request->validated();
            $valid['user_id'] = $id->id;
            
            $data = Job::create($valid);
            return $this->create_response(true,  'ok', $data, 201);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //show 

    public function show($lang, Job $job)
    {
        $this->authorizeCheck('job view');

        try {
            $data = $job;
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }
    //update

    public function update($lang, UpdateJobRequest $request, Job $job)
    {
        try {

            $valid = $request->validated();
            
            $job->update($valid);
            $data = Job::findOrFail($job->id);
            return $this->create_response(true,  'ok', $data, 202);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //destroy

    public function destroy($lang, Job $job)
    {
        $this->authorizeCheck('job delete');
        try {
            $job->delete();
            return $this->create_response(true,  'ok', $job, 203);

            // return $this->create_response(false,  'not ok', $data, 422);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage(), null, 500);
        }
    }
}
