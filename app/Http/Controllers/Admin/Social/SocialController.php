<?php

namespace App\Http\Controllers\Admin\Social;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Social\StoreSocialRequest;
use App\Http\Requests\Admin\Social\UpdateSocialRequest;
use App\Models\Social;
use App\Traits\AuthorizeCheck;
use App\Traits\CreateResponse;
use App\Traits\ImageProccessing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    use AuthorizeCheck, CreateResponse, ImageProccessing;

    public function index()
    {
        $this->authorizeCheck('social view');
        try {
            $data = Social::all();
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //store

    public function store(StoreSocialRequest $request)
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
            $data = Social::create($valid);
            return $this->create_response(true,  'ok', $data, 201);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //show 

    public function show($lang, Social $social)
    {
        $this->authorizeCheck('social view');

        try {
            $data = $social;
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }
    //update

    public function update($lang, UpdateSocialRequest $request, Social $social)
    {
        try {

            $valid = $request->validated();
            if ($request->hasFile('photo')) {
                $social->photo ? $this->deleteImage($social->photo) : '';
                $mime = $request->file('photo')->getMimeType();
                $ext = $this->getExtensionFromMime($mime);
                $valid['photo'] = $this->saveImage($request->photo, $ext);
            }
            $social->update($valid);
            $data = Social::findOrFail($social->id);
            return $this->create_response(true,  'ok', $data, 202);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //destroy

    public function destroy($lang, Social $social)
    {
        $this->authorizeCheck('social delete');
        try {
            $social->photo ? $this->deleteImage($social->photo) : '';
            $social->delete();
            return $this->create_response(true,  'ok', $social, 203);

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
