<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
define('URL_LENGTH', 5);
// const 
// static

// Document
// REPOsitory - GIT
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('encode', function(Request $request){

	// Get the URL
	$url = $request->get('url');

	//	Strategy for making it short
		//	1. Hashing -> length 255
		//	2. Custom
			//	Unique values (UTC time(), )
			//	rand function -> 
			//	case sensitive // 3srfxwM 
			//	
		//	3. JWT -> length 255
		//	
	// Track the IP
		//	[3srfxwM] => google.com
	$urlHash = makeHash($url);
	Cache::put($urlHash, $url);

	return response()->json([
	    'url' => env('APP_URL') . '/api/short/' .$urlHash
	]);
});

Route::get('decode', function(Request $request){
	$code_value = getLinkFromCode($request->get('code'));
	return response()->json([
	    'url' => $code_value
	]);
});

Route::get('short/{short_code}', function(Request $request, $short_code){
	
	header('Location: ' . getLinkFromCode($short_code));
	exit;
});

function getLinkFromCode($code){
	$value = Cache::get($code);
	return $value;
}

function makeHash($url){
	$hash = [];
	array_push($hash, rand(1, 100));
	$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	for ($i=1; $i < URL_LENGTH; $i++) { 
		$index = rand(1,51);
		array_push( $hash, $str[$index] );
	}
	return implode("", $hash);
}
// SOLID
/*
 * 1. public method for hash
 * 2. private method for creating the Complex hash
 *		combine the WHOLE URL
 */
function decryptHash($hash){
	// $cache = Cache::get();
}