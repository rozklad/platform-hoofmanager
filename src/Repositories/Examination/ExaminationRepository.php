<?php namespace Sanatorium\Hoofmanager\Repositories\Examination;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class ExaminationRepository implements ExaminationRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Hoofmanager\Handlers\Examination\ExaminationDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent hoofmanager model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.hoofmanager.examination.handler.data'];

		$this->setValidator($app['sanatorium.hoofmanager.examination.validator']);

		$this->setModel(get_class($app['Sanatorium\Hoofmanager\Models\Examination']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.examination.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.examination.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new examination
		$examination = $this->createModel();

		// Fire the 'sanatorium.hoofmanager.examination.creating' event
		if ($this->fireEvent('sanatorium.hoofmanager.examination.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the examination
			$examination->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.examination.created' event
			$this->fireEvent('sanatorium.hoofmanager.examination.created', [ $examination ]);
		}

		return [ $messages, $examination ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the examination object
		$examination = $this->find($id);

		// Fire the 'sanatorium.hoofmanager.examination.updating' event
		if ($this->fireEvent('sanatorium.hoofmanager.examination.updating', [ $examination, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($examination, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the examination
			$examination->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.examination.updated' event
			$this->fireEvent('sanatorium.hoofmanager.examination.updated', [ $examination ]);
		}

		return [ $messages, $examination ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the examination exists
		if ($examination = $this->find($id))
		{
			// Fire the 'sanatorium.hoofmanager.examination.deleted' event
			$this->fireEvent('sanatorium.hoofmanager.examination.deleted', [ $examination ]);

			// Delete the examination entry
			$examination->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
