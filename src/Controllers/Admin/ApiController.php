<?php namespace Sanatorium\Hoofmanager\Controllers\Admin;

use Platform\Access\Controllers\AdminController;

class ApiController extends AdminController {


	/**
	 * Display a listing of chapter.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/hoofmanager::info');
	}


	public function info()
	{
		return view('sanatorium/hoofmanager::info',
			[
				'calls' => \Sanatorium\Hoofmanager\Controllers\Api\ApiController::$calls
			]
			);
	}

}
