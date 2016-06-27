<?php namespace Sanatorium\Hoofmanager\Controllers\Api;

use Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepositoryInterface;
use Sanatorium\Hoofmanager\Repositories\Treatment\TreatmentRepositoryInterface;

use Input;

class TreatmentsController extends ApiController {

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
	 * @param  \Sanatorium\Hoofmanager\Repositories\Treatments\TreatmentsRepositoryInterface  $treatments
	 * @return void
	 */
	public function __construct(ApilogRepositoryInterface $apilogs,
		TreatmentRepositoryInterface $treatments)
	{
		parent::__construct($apilogs);

		$this->treatments = $treatments;
	}

	/**
	 * Display a listing of treatments.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/hoofmanager::treatments.index');
	}

	/**
	 * Datasource for the treatments Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->treatments->grid();

		$columns = [
			'*',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.hoofmanager.treatments.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new treatments.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new treatments.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating treatments.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating treatments.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified treatments.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->treatments->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/hoofmanager::treatments/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.hoofmanager.treatments.all');
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
				$this->treatments->{$action}($row);
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
		// Do we have a treatments identifier?
		if (isset($id))
		{
			if ( ! $treatment = $this->treatments->find($id))
			{
				$this->alerts->error(trans('sanatorium/hoofmanager::treatments/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.hoofmanager.treatments.all');
			}
		}
		else
		{
			$treatment = $this->treatments->createModel();
		}

		// Show the page
		return view('sanatorium/hoofmanager::treatments.form', compact('mode', 'treatment'));
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
		if ( $name = request()->get('name') ) {
			if ( $treatment = $this->treatments()->where('name', $name)->first() ) {
				$id = $treatment->id;
			}
		}

		// Store the treatments
		list($messages) = $this->treatments->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/hoofmanager::treatments/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.hoofmanager.treatments.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

	public function treatments() {
		return app("sanatorium.hoofmanager.treatment")->get();
	}

}

