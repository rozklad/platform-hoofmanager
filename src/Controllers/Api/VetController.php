<?php namespace Sanatorium\Hoofmanager\Controllers\Api;

use Input;
use Sanatorium\Hoofmanager\Models\Vet;
use Sanatorium\Hoofmanager\Models\House;
use Sanatorium\Hoofmanager\Models\Finding;
use Sanatorium\Hoofmanager\Models\Examination;
use Sanatorium\Hoofmanager\Models\Item;
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

		if ( $vet->isAdmin() ) {

			foreach( House::all() as $house ) {
				$houses[$house->id] = $house;
			}

		} else {

			foreach( House::all()->where('user_id', $vet->id) as $house ) {
				$houses[$house->id] = $house;
			}

		}

		$vet->houses = $houses;

		return $vet;
	}

	public function checks($id = null)
	{
		$vet = Vet::find($id);

		$checks = [];

		$items = [];

		$findings = Finding::whereHas('examination', function($q)  use ($vet) {

			return $q->where('user_id', $vet->id);

		})->whereNotNull('check_date')->where('check_date', '!=', '0000-00-00 00:00:00')->get();

		foreach ($findings as $finding) {
			
			$item_id = $finding->examination()->first()->item_id;

			if ( Item::find($item_id) ) {

				$item = Item::find($item_id);

				$house = $item->houses()->first();

				$finding['cattle_number'] = $house->cattle_number;

				$finding['item_number'] = $item->item_number;

				/*$examination[$finding->id] = [$finding];

				$items[$item_id] = ['item_id' => $item_id, 'item_number' => $item->item_number, 'findings' => $examination];

				$checks[$house->id] = ['house_id' => $house->id, 'cattle_number' => $house->cattle_number, 'items' => $items];*/

			}

		}

		return $findings;
		
	}
}
