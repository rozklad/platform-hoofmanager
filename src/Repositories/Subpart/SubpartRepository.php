<?php namespace Sanatorium\Hoofmanager\Repositories\Subpart;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class SubpartRepository implements SubpartRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Hoofmanager\Handlers\Subpart\SubpartDataHandlerInterface
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

		$this->data = $app['sanatorium.hoofmanager.subpart.handler.data'];

		$this->setValidator($app['sanatorium.hoofmanager.subpart.validator']);

		$this->setModel(get_class($app['Sanatorium\Hoofmanager\Models\Subpart']));
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
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.subpart.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.subpart.'.$id, function() use ($id)
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
		// Create a new subpart
		$subpart = $this->createModel();

		// Fire the 'sanatorium.hoofmanager.subpart.creating' event
		if ($this->fireEvent('sanatorium.hoofmanager.subpart.creating', [ $input ]) === false)
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
			// Save the subpart
			$subpart->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.subpart.created' event
			$this->fireEvent('sanatorium.hoofmanager.subpart.created', [ $subpart ]);
		}

		return [ $messages, $subpart ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the subpart object
		$subpart = $this->find($id);

		// Fire the 'sanatorium.hoofmanager.subpart.updating' event
		if ($this->fireEvent('sanatorium.hoofmanager.subpart.updating', [ $subpart, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($subpart, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the subpart
			$subpart->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.subpart.updated' event
			$this->fireEvent('sanatorium.hoofmanager.subpart.updated', [ $subpart ]);
		}

		return [ $messages, $subpart ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the subpart exists
		if ($subpart = $this->find($id))
		{
			// Fire the 'sanatorium.hoofmanager.subpart.deleted' event
			$this->fireEvent('sanatorium.hoofmanager.subpart.deleted', [ $subpart ]);

			// Delete the subpart entry
			$subpart->delete();

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
