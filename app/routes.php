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

function updateProgress($message, $progress) {
	Cache::put('queue:message', $message, 30);
	Cache::put('queue:progress', $progress, 30);
}

Route::get('/', function()
{
	return View::make('index');
});

Route::get('start', function()
{
	// Reset parameters
	updateProgress('', 0);

	Queue::push(function($job) {
		updateProgress('Just started', 5);
		sleep(1);

		updateProgress('Going to sleep', 10);
		sleep(5);

		updateProgress('Done sleeping', 50);
		sleep(5);
		
		updateProgress('Done!', 100);

		$job->delete();
	});

	return Response::json(array('success' => true));
});

Route::get('start-identity', function()
{
	// Reset parameters
	updateProgress('', 0);

	Queue::push(function($job) {
		$faker = Faker\Factory::create();

		for ($i = 1; $i < 20; $i++) {
			updateProgress('Generating identity for ' . $faker->name, $i / 20 * 100);
			sleep(1);
		}

		updateProgress('Done!', 100);

		$job->delete();
	});

	return Response::json(array('success' => true));
});

Route::get('start-geocode', function()
{
	// Reset parameters
	updateProgress('', 0);

	Queue::push(function($job) {
		$faker = Faker\Factory::create();

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