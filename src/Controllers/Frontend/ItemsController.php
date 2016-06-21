<<<<<<< HEAD
<?php namespace Sanatorium\Hoofmanager\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Sanatorium\Hoofmanager\Models\Vet;
use Sanatorium\Hoofmanager\Models\House;
use Sanatorium\Hoofmanager\Models\Item;

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

		$vet = Vet::getVet();

		$items = app('sanatorium.hoofmanager.items');

		$examinations = app('sanatorium.hoofmanager.examination')->orderBy('created_at', 'DESC');

		$examinations = $examinations->where('item_id', $id)->get();

		$houses = House::where('user_id', $vet->id)->get();

		$diseases = app('sanatorium.hoofmanager.diseases')->findAll();

		$treatments = app('sanatorium.hoofmanager.treatment')->findAll();

		$house = $items->find($id)->houses()->first();

		if ( $id ) 
		{
			$item = $items->find($id);
		} else 
		{
			$item = $items->createModel();
		}

		return view('sanatorium/hoofmanager::items/detail', compact('item', 'examinations', 'houses', 'house', 'diseases', 'treatments'));
	}

	public function update($id)
	{
		$item = Item::find($id);

		$items = app('sanatorium.hoofmanager.items');

		$actual_cattle = Item::find($id)->houses()->first();

		if ( $actual_cattle->id != request()->cattle_id ) {

			$new_cattle = House::find( request()->cattle_id );

			$item->houses()->sync([$new_cattle->id]);

		}

		if ( $item->collar != request()->collar ) {

			$items->store($id, request()->only('collar'));

		}

		if ( $item->birthday != request()->birthday ) {

			$items->store($id, request()->only('birthday'));

		}

		return redirect()->back();
		
		/*if ( request()->has('collar') )
		{

			return $this->processFormItem('update', $id);

		} else {

			return $this->processFormFinding('update', $id);

		}*/
		
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
=======
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
>>>>>>> 434c7e6f18a18ec990cc7933cb97003437355935
