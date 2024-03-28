<?php

namespace App\Http\Controllers\Admin\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Certification\StoreCertificRequest;
use App\Http\Requests\Admin\Certification\UpdateCertificRequest;
use App\Models\Certifications;
use App\Traits\AuthorizeCheck;
use App\Traits\CreateResponse;
use App\Traits\ImageProccessing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertifiController extends Controller
{
    use AuthorizeCheck, CreateResponse, ImageProccessing;

    public function index()
    {
        $this->authorizeCheck('certification view');
        try {
            $data = Certifications::all();
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //store

    public function store(StoreCertificRequest $request)
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
            $data = Certifications::create($valid);
            return $this->create_response(true,  'ok', $data, 201);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //show 

    public function show($lang, Certifications $certification)
    {
        $this->authorizeCheck('certification view');

        try {
            $data = $certification;
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }
    //update

    public function update($lang, UpdateCertificRequest $request, Certifications $certification)
    {
        try {

            $valid = $request->validated();
            if ($request->hasFile('photo')) {
                $certification->photo ? $this->deleteImage($certification->photo) : '';
                $mime = $request->file('photo')->getMimeType();
                $ext = $this->getExtensionFromMime($mime);
                $valid['photo'] = $this->saveImage($request->photo, $ext);
            }
            $certification->update($valid);
            $data = Certifications::findOrFail($certification->id);
            return $this->create_response(true,  'ok', $data, 202);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //destroy

    public function destroy($lang, Certifications $certification)
    {
        $this->authorizeCheck('certification delete');
        try {
            $certification->photo ? $this->deleteImage($certification->photo) : '';
            $certification->delete();
            return $this->create_response(true,  'ok', $certification, 203);

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
