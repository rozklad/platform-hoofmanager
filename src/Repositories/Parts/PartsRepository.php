<?php namespace Sanatorium\Hoofmanager\Repositories\Parts;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class PartsRepository implements PartsRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Hoofmanager\Handlers\Parts\PartsDataHandlerInterface
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

		$this->data = $app['sanatorium.hoofmanager.parts.handler.data'];

		$this->setValidator($app['sanatorium.hoofmanager.parts.validator']);

		$this->setModel(get_class($app['Sanatorium\Hoofmanager\Models\Part']));
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
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.parts.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.parts.'.$id, function() use ($id)
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
		// Create a new parts
		$parts = $this->createModel();

		// Fire the 'sanatorium.hoofmanager.parts.creating' event
		if ($this->fireEvent('sanatorium.hoofmanager.parts.creating', [ $input ]) === false)
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
			// Save the parts
			$parts->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.parts.created' event
			$this->fireEvent('sanatorium.hoofmanager.parts.created', [ $parts ]);
		}

		return [ $messages, $parts ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the parts object
		$parts = $this->find($id);

		// Fire the 'sanatorium.hoofmanager.parts.updating' event
		if ($this->fireEvent('sanatorium.hoofmanager.parts.updating', [ $parts, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($parts, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the parts
			$parts->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.parts.updated' event
			$this->fireEvent('sanatorium.hoofmanager.parts.updated', [ $parts ]);
		}

		return [ $messages, $parts ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the parts exists
		if ($parts = $this->find($id))
		{
			// Fire the 'sanatorium.hoofmanager.parts.deleted' event
			$this->fireEvent('sanatorium.hoofmanager.parts.deleted', [ $parts ]);

			// Delete the parts entry
			$parts->delete();

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
