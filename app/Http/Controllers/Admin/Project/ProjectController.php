<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\StoreProjectRequest;
use App\Http\Requests\Admin\Project\UpdateProjectRequest;
use App\Models\Project;
use App\Traits\AuthorizeCheck;
use App\Traits\CreateResponse;
use App\Traits\ImageProccessing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    use AuthorizeCheck, CreateResponse, ImageProccessing;

    public function index()
    {
        $this->authorizeCheck('project view');
        try {
            $data = Project::all();
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //store

    public function store(StoreProjectRequest $request)
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
            $data = Project::create($valid);
            return $this->create_response(true,  'ok', $data, 201);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //show 

    public function show($lang, Project $project)
    {
        $this->authorizeCheck('project view');

        try {
            $data = $project;
            return $this->create_response(true,  'ok', $data, 200);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }
    //update

    public function update($lang, UpdateProjectRequest $request, Project $project)
    {
        try {

            $valid = $request->validated();
            if ($request->hasFile('photo')) {
                $project->photo ? $this->deleteImage($project->photo) : '';
                $mime = $request->file('photo')->getMimeType();
                $ext = $this->getExtensionFromMime($mime);
                $valid['photo'] = $this->saveImage($request->photo, $ext);
            }
            $project->update($valid);
            $data = Project::findOrFail($project->id);
            return $this->create_response(true,  'ok', $data, 202);
        } catch (\Exception $e) {
            return $this->create_response(false,  $e->getMessage());
        }
    }

    //destroy

    public function destroy($lang, Project $project)
    {
        $this->authorizeCheck('project delete');
        try {
            $project->photo ? $this->deleteImage($project->photo) : '';
            $project->delete();
            return $this->create_response(true,  'ok', $project, 203);

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
