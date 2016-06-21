<?php namespace Sanatorium\Hoofmanager\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Hoofmanager\Repositories\Items\ItemsRepositoryInterface;

class ItemsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

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
	 * @param  \Sanatorium\Hoofmanager\Repositories\Items\ItemsRepositoryInterface  $items
	 * @return void
	 */
	public function __construct(ItemsRepositoryInterface $items)
	{
		parent::__construct();

		$this->items = $items;
	}

	/**
	 * Display a listing of items.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/hoofmanager::items.index');
	}

	/**
	 * Datasource for the items Data Grid.
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
		// Do we have a items identifier?
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
		return view('sanatorium/hoofmanager::items.form', compact('mode', 'items'));
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
		// Store the items
		list($messages) = $this->items->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/hoofmanager::items/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.hoofmanager.items.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
