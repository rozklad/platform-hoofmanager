<?php namespace Sanatorium\Hoofmanager\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepositoryInterface;

class ApilogsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Hoofmanager repository.
	 *
	 * @var \Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepositoryInterface
	 */
	protected $apilogs;

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
	 * @param  \Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepositoryInterface  $apilogs
	 * @return void
	 */
	public function __construct(ApilogRepositoryInterface $apilogs)
	{
		parent::__construct();

		$this->apilogs = $apilogs;
	}

	/**
	 * Display a listing of apilog.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/hoofmanager::apilogs.index');
	}

	/**
	 * Datasource for the apilog Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->apilogs->grid();

		$columns = [
			'id',
			'call',
			'data',
			'method',
			'status',
			'source',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.hoofmanager.apilogs.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new apilog.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new apilog.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating apilog.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating apilog.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified apilog.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->apilogs->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/hoofmanager::apilogs/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.hoofmanager.apilogs.all');
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
				$this->apilogs->{$action}($row);
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
		// Do we have a apilog identifier?
		if (isset($id))
		{
			if ( ! $apilog = $this->apilogs->find($id))
			{
				$this->alerts->error(trans('sanatorium/hoofmanager::apilogs/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.hoofmanager.apilogs.all');
			}
		}
		else
		{
			$apilog = $this->apilogs->createModel();
		}

		// Show the page
		return view('sanatorium/hoofmanager::apilogs.form', compact('mode', 'apilog'));
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
		// Store the apilog
		list($messages) = $this->apilogs->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/hoofmanager::apilogs/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.hoofmanager.apilogs.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
