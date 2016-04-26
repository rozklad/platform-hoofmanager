<?php namespace Sanatorium\Hoofmanager\Repositories\Items;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class ItemsRepository implements ItemsRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Hoofmanager\Handlers\Items\ItemsDataHandlerInterface
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

		$this->data = $app['sanatorium.hoofmanager.items.handler.data'];

		$this->setValidator($app['sanatorium.hoofmanager.items.validator']);

		$this->setModel(get_class($app['Sanatorium\Hoofmanager\Models\Item']));
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
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.items.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.items.'.$id, function() use ($id)
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
		// Create a new items
		$items = $this->createModel();

		// Fire the 'sanatorium.hoofmanager.items.creating' event
		if ($this->fireEvent('sanatorium.hoofmanager.items.creating', [ $input ]) === false)
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
			// Save the items
			$items->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.items.created' event
			$this->fireEvent('sanatorium.hoofmanager.items.created', [ $items ]);
		}

		return [ $messages, $items ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the items object
		$items = $this->find($id);

		// Fire the 'sanatorium.hoofmanager.items.updating' event
		if ($this->fireEvent('sanatorium.hoofmanager.items.updating', [ $items, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($items, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the items
			$items->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.items.updated' event
			$this->fireEvent('sanatorium.hoofmanager.items.updated', [ $items ]);
		}

		return [ $messages, $items ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the items exists
		if ($items = $this->find($id))
		{
			// Fire the 'sanatorium.hoofmanager.items.deleted' event
			$this->fireEvent('sanatorium.hoofmanager.items.deleted', [ $items ]);

			// Delete the items entry
			$items->delete();

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
