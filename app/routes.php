<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('index');
});

function updateProgress($message, $progress) {
	Cache::put('queue:message', $message, 30);
	Cache::put('queue:progress', $progress, 30);
}

Route::get('start', function()
{
	// Reset parameters
	updateProgress('', 0);

	Queue::push(function($job) {
		for ($i = 1; $i < 30; $i++) {
			$loc = mt_rand(0, 9000) / 100 .','. mt_rand(-18000, 18000) / 100;
			$response = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='. $loc .'&sensor=false'));

			if (count($response->results) > 0) {
				$message = $response->results[0]->formatted_address;
			} else {
				$message = 'No match';
			}
			updateProgress($loc . ': ' . $message, $i / 30 * 100);
			sleep(1);
		}

		updateProgress('Done!', 100);

		$job->delete();
	});

	return Response::json(array('success' => true));
});

Route::get('status', function()
{
	$response = [
		'progress' => Cache::get('queue:progress'),
		'message' => Cache::get('queue:message')
	];
	return Response::json($response);
});
