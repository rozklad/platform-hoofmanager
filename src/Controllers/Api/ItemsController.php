<?php namespace Sanatorium\Hoofmanager\Controllers\Api;

use Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepositoryInterface;
use Sanatorium\Hoofmanager\Repositories\Houses\HousesRepositoryInterface;
use Sanatorium\Hoofmanager\Repositories\Items\ItemsRepositoryInterface;

use Input;

class ItemsController extends ApiController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Hoofmanager repository.
	 *
	 * @var \Sanatorium\Hoofmanager\Repositories\Houses\HousesRepositoryInterface
	 */
	protected $houses;

	/**
	 * The Hoofmanager repository.
	 *
	 * @var \Sanatorium\Hoofmanager\Repositories\Items\ItemsRepositoryInterface
	 */
	protected $items;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Hoofmanager\Repositories\Houses\HousesRepositoryInterface  $houses
	 * @param  \Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepositoryInterface $apilogs
	 * @return void
	 */
	public function __construct(ApilogRepositoryInterface $apilogs,
		HousesRepositoryInterface $houses,
		ItemsRepositoryInterface $items
		)
	{
		parent::__construct($apilogs);

		$this->houses = $houses;

		$this->apilogs = $apilogs;

		$this->items = $items;
	}

	/**
	 * Display a listing of houses.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/hoofmanager::items.index');
	}

	/**
	 * Datasource for the houses Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->items->grid();

		$columns = [
			'id',
			'item_number',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.hoofmanager.items.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	public function simple()
	{
		$cols = Input::has('cols') ? Input::get('cols') : ['id', 'item_number'];

		$data = $this->items->all();
		
		$result = [];

		foreach( $data as $item ) {
			$result_item = [];

			foreach( $cols as $col ) {
				$result_item[$col] = $item->{$col};
			}

			$result[] = $result_item;
		}

		return $result;
	}

	public function view($id)
	{
		$item = $this->items->with('houses', 'examinations')->find($id);

		return $item;
	}

	public function viewByNumber($item_number)
	{
		$item = $this->items->with('houses', 'examinations')->where('item_number', $item_number)->first();

		return $item;
	}

	/**
	 * Show the form for creating new items.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new items.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating items.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating items.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified items.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->items->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/hoofmanager::items/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.hoofmanager.items.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->items->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a houses identifier?
		if (isset($id))
		{
			if ( ! $items = $this->items->find($id))
			{
				$this->alerts->error(trans('sanatorium/hoofmanager::items/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.hoofmanager.items.all');
			}
		}
		else
		{
			$items = $this->items->createModel();
		}

		// Show the page
		return view('sanatorium/hoofmanager::items.form', compact('mode', 'houses'));
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
		// Store the houses
		list($messages, $item) = $this->items->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			return $item;
		}

		return ['success' => false];
	}

}
