<?php namespace Sanatorium\Hoofmanager\Repositories\Treatment;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class TreatmentRepository implements TreatmentRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Hoofmanager\Handlers\Treatment\TreatmentDataHandlerInterface
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

		$this->data = $app['sanatorium.hoofmanager.treatment.handler.data'];

		$this->setValidator($app['sanatorium.hoofmanager.treatment.validator']);

		$this->setModel(get_class($app['Sanatorium\Hoofmanager\Models\Treatment']));
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
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.treatment.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.treatment.'.$id, function() use ($id)
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
		// Create a new treatment
		$treatment = $this->createModel();

		// Fire the 'sanatorium.hoofmanager.treatment.creating' event
		if ($this->fireEvent('sanatorium.hoofmanager.treatment.creating', [ $input ]) === false)
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
			// Save the treatment
			$treatment->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.treatment.created' event
			$this->fireEvent('sanatorium.hoofmanager.treatment.created', [ $treatment ]);
		}

		return [ $messages, $treatment ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the treatment object
		$treatment = $this->find($id);

		// Fire the 'sanatorium.hoofmanager.treatment.updating' event
		if ($this->fireEvent('sanatorium.hoofmanager.treatment.updating', [ $treatment, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($treatment, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the treatment
			$treatment->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.treatment.updated' event
			$this->fireEvent('sanatorium.hoofmanager.treatment.updated', [ $treatment ]);
		}

		return [ $messages, $treatment ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the treatment exists
		if ($treatment = $this->find($id))
		{
			// Fire the 'sanatorium.hoofmanager.treatment.deleted' event
			$this->fireEvent('sanatorium.hoofmanager.treatment.deleted', [ $treatment ]);

			// Delete the treatment entry
			$treatment->delete();

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
