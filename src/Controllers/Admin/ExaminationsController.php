<?php namespace Sanatorium\Hoofmanager\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Hoofmanager\Repositories\Examination\ExaminationRepositoryInterface;

class ExaminationsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Hoofmanager repository.
	 *
	 * @var \Sanatorium\Hoofmanager\Repositories\Examination\ExaminationRepositoryInterface
	 */
	protected $examinations;

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
	 * @param  \Sanatorium\Hoofmanager\Repositories\Examination\ExaminationRepositoryInterface  $examinations
	 * @return void
	 */
	public function __construct(ExaminationRepositoryInterface $examinations)
	{
		parent::__construct();

		$this->examinations = $examinations;
	}

	/**
	 * Display a listing of examination.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/hoofmanager::examinations.index');
	}

	/**
	 * Datasource for the examination Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->examinations->grid();

		$columns = [
			'id',
			'user_id',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.hoofmanager.examinations.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new examination.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new examination.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating examination.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating examination.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified examination.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->examinations->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/hoofmanager::examinations/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.hoofmanager.examinations.all');
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
				$this->examinations->{$action}($row);
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
		// Do we have a examination identifier?
		if (isset($id))
		{
			if ( ! $examination = $this->examinations->find($id))
			{
				$this->alerts->error(trans('sanatorium/hoofmanager::examinations/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.hoofmanager.examinations.all');
			}
		}
		else
		{
			$examination = $this->examinations->createModel();
		}

		// Show the page
		return view('sanatorium/hoofmanager::examinations.form', compact('mode', 'examination'));
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
		// Store the examination
		list($messages) = $this->examinations->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/hoofmanager::examinations/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.hoofmanager.examinations.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
