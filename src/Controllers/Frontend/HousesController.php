<?php namespace Sanatorium\Hoofmanager\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class HousesController extends Controller {

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
		return $this->showForm($id);
	}

	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	public function showForm($id = null)
	{
		$houses = app('sanatorium.hoofmanager.houses');

		if ( $id ) 
		{
			$house = $houses->find($id);
		} else 
		{
			$house = $houses->createModel();
		}

		return view('sanatorium/hoofmanager::houses/form', compact('house'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		$this->houses = app('sanatorium.hoofmanager.houses');

		// Store the houses
		list($messages) = $this->houses->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/hoofmanager::houses/message.success.{$mode}"));

			return redirect()->route('sanatorium.hoofmanager.front');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
