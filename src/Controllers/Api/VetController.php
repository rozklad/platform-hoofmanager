<?php namespace Sanatorium\Hoofmanager\Controllers\Api;

use Input;
use Sanatorium\Hoofmanager\Models\Vet;
use Sanatorium\Hoofmanager\Models\House;
use Sentinel;

class VetController extends ApiController {


	/**
	 * Show the form for creating new houses.
	 *
	 * @return \Illuminate\View\View
	 */
	public function auth()
	{
		$credentials = [
			'email'    => Input::get('email'),
			'password' => Input::get('password'),
		];

		if ($user = Sentinel::authenticate($credentials))
		{
			$this->status = 200;
			$this->result = $this->find($user->id);
		}
		else
		{
			$this->status = 403;
    		$this->result = ['success' => false];
		}
		return $this->result;
	}

	public function find($id = null)
	{
		// @todo make more efficient
		$vet = Vet::find($id);

		$houses = [];

		foreach( House::all() as $house ) {
			$houses[$house->id] = $house;
		}

		$vet->houses = $houses;

		return $vet;
	}
}
