<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

require_once __DIR__ . '/Api/auth.php';
require_once __DIR__ . '/Api/country.php';
Route::get('portofolio',function(){
    try {
        // Fetch user data with all related data
        $user = User::getUserWithRelatedData(1);

        return $user;
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500); // Handle any exceptions
    }

});
// require_once __DIR__ . '/Api/role.php';