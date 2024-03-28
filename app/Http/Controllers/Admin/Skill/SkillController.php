<?php

namespace App\Http\Controllers\Admin\Skill;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Skill\StoreSkillRequest;
use App\Http\Requests\Admin\Skill\UpdateSkillRequest;
use App\Models\Skill;
use App\Traits\AuthorizeCheck;
use App\Traits\CreateResponse;
use App\Traits\ImageProccessing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    use AuthorizeCheck, CreateResponse, ImageProccessing;

    public function index()
    {
        $this->authorizeCheck('skill view');
        try {
            $data = Skill::all();
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //store

    public function store(StoreSkillRequest $request)
    {
        try {
            $id = Auth::user();

            $valid = $request->validated();
            $valid['user_id'] = $id->id;
            if ($request->hasFile('photo')) {
                $mime = $request->file('photo')->getMimeType();
                $ext = $this->getExtensionFromMime($mime);
                $valid['photo'] = $this->saveImage($request->photo, $ext);
            }
            $data = Skill::create($valid);
            return $this->create_response(true,  'ok', $data, 201);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //show 

    public function show($lang, Skill $skill)
    {
        $this->authorizeCheck('skill view');

        try {
            $data = $skill;
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }
    //update

    public function update($lang, UpdateSkillRequest $request, Skill $skill)
    {
        try {

            $valid = $request->validated();
            if ($request->hasFile('photo')) {
                $skill->photo ? $this->deleteImage($skill->photo) : '';
                $mime = $request->file('photo')->getMimeType();
                $ext = $this->getExtensionFromMime($mime);
                $valid['photo'] = $this->saveImage($request->photo, $ext);
            }
            $skill->update($valid);
            $data = Skill::findOrFail($skill->id);
            return $this->create_response(true,  'ok', $data, 202);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //destroy

    public function destroy($lang, Skill $skill)
    {
        $this->authorizeCheck('skill delete');
        try {
            $skill->photo ? $this->deleteImage($skill->photo) : '';

            $skill->delete();
            return $this->create_response(true,  'ok', $skill, 203);

            // return $this->create_response(false,  'not ok', $data, 422);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage(), null, 500);
        }
    }




    private function getExtensionFromMime($mime)
    {
        $extensions = [
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
            'image/gif' => '.gif',
            // Add more MIME type to extension mappings as needed
        ];

        // Default extension if not found
        $defaultExtension = '.jpg';

        return $extensions[$mime] ?? $defaultExtension;
    }
}
