<?php namespace Sanatorium\Hoofmanager\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Hoofmanager\Repositories\Examination\ExaminationRepositoryInterface;

use Sentinel;

class VetController extends AdminController {

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
		return view('sanatorium/hoofmanager::vet.index');
	}

	/**
	 * Datasource for the examination Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$user = Sentinel::getUser();
		/* $user->id*/
		$data = $this->examinations->grid()->with('item.houses')->where('user_id', $user->id);

		$columns = [
			'id',
			'item_id',
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

			$houses = [];

			if (is_object($element->item)) {
				foreach( $element->item->houses as $house ) {
					$houses[$house->id] = $house;
				}
			}

			$element->houses = $houses;

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}


}
