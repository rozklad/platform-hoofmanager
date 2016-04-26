<?php namespace Sanatorium\Hoofmanager\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class ItemsController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/hoofmanager::index');
	}

	public function edit($id)
	{

		$items = app('sanatorium.hoofmanager.items');

		$examinations = app('sanatorium.hoofmanager.examination')->orderBy('created_at', 'DESC');

		$examinations = $examinations->where('item_id', $id)->get();

		$houses = app('sanatorium.hoofmanager.houses')->findAll();

		$diseases = app('sanatorium.hoofmanager.diseases')->findAll();

		$treatments = app('sanatorium.hoofmanager.treatment')->findAll();

		for ( $i = 0; $i < count($houses); $i++)
		{
			if (count($houses[$i]->items->where('id', $id)) > 0)
			{
				$house = $houses[$i];

				break;
			}

		}

		if ( $id ) 
		{
			$item = $items->find($id);
		} else 
		{
			$item = $items->createModel();
		}

		return view('sanatorium/hoofmanager::items/detail', compact('item', 'examinations', 'house', 'diseases', 'treatments'));
	}

	public function update($id)
	{
		if ( request()->has('collar') )
		{

			return $this->processFormItem('update', $id);

		} else {

			return $this->processFormFinding('update', $id);

		}
		
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processFormItem($mode, $id = null)
	{
		$this->items = app('sanatorium.hoofmanager.items');

		// Store the items
		list($messages) = $this->items->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/hoofmanager::houses/message.success.{$mode}"));

			return redirect()->back();
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

	protected function processFormFinding($mode, $id = null)
	{

		$this->findings = app('sanatorium.hoofmanager.finding');

		$finding_id = request()->input('finding_id');
		
		// Store the finding
		list($messages) = $this->findings->store($finding_id, request()->except('finding_id'));

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/hoofmanager::houses/message.success.{$mode}"));

			return redirect()->back();
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
