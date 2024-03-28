<?php

use App\Http\Controllers\Admin\Certification\CertifiController;
use App\Http\Controllers\Admin\Contact\ContactController;
use App\Http\Controllers\Admin\Education\EduController;
use App\Http\Controllers\Admin\Job\JobController;
use App\Http\Controllers\Admin\Project\ProjectController;
use App\Http\Controllers\Admin\Skill\SkillController;
use App\Http\Controllers\Admin\Social\SocialController;
use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum','setapplang'])->prefix('{locale}')->group(function(){
  Route::post('projects', [ProjectController::class , 'store']);
  Route::post('projects/{project}', [ProjectController::class , 'update']);
  Route::get('projects', [ProjectController::class , 'index']);
  Route::get('projects/{project}', [ProjectController::class , 'show']);
  Route::delete('projects/{project}', [ProjectController::class , 'destroy']);
  
  //skills Routes

  Route::post('skills', [SkillController::class , 'store']);
  Route::post('skills/{skill}', [SkillController::class , 'update']);
  Route::get('skills', [SkillController::class , 'index']);
  Route::get('skills/{skill}', [SkillController::class , 'show']);
  Route::delete('skills/{skill}', [SkillController::class , 'destroy']);

  // Certification

  Route::post('certifications', [CertifiController::class , 'store']);
  Route::post('certifications/{certification}', [CertifiController::class , 'update']);
  Route::get('certifications', [CertifiController::class , 'index']);
  Route::get('certifications/{certification}', [CertifiController::class , 'show']);
  Route::delete('certifications/{certification}', [CertifiController::class , 'destroy']);

  //social

  Route::post('socialmedia', [SocialController::class , 'store']);
  Route::post('socialmedia/{social}', [SocialController::class , 'update']);
  Route::get('socialmedia', [SocialController::class , 'index']);
  Route::get('socialmedia/{social}', [SocialController::class , 'show']);
  Route::delete('socialmedia/{social}', [SocialController::class , 'destroy']);

  //education

  Route::post('educations', [EduController::class , 'store']);
  Route::put('educations/{education}', [EduController::class , 'update']);
  Route::get('educations', [EduController::class , 'index']);
  Route::get('educations/{education}', [EduController::class , 'show']);
  Route::delete('educations/{education}', [EduController::class , 'destroy']);

   //job

   Route::post('jobs', [JobController::class , 'store']);
   Route::put('jobs/{job}', [JobController::class , 'update']);
   Route::get('jobs', [JobController::class , 'index']);
   Route::get('jobs/{job}', [JobController::class , 'show']);
   Route::delete('jobs/{job}', [JobController::class , 'destroy']);

   //mail

   Route::apiResource('contacts', ContactController::class)->except('update','store');

});

Route::middleware(['setapplang'])->prefix('{locale}')->group(function(){
    Route::post('mail',[ContactController::class , 'store']);
});