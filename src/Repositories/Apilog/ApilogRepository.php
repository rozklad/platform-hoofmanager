<?php namespace Sanatorium\Hoofmanager\Repositories\Apilog;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class ApilogRepository implements ApilogRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Hoofmanager\Handlers\Apilog\ApilogDataHandlerInterface
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

		$this->data = $app['sanatorium.hoofmanager.apilog.handler.data'];

		$this->setValidator($app['sanatorium.hoofmanager.apilog.validator']);

		$this->setModel(get_class($app['Sanatorium\Hoofmanager\Models\Apilog']));
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
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.apilog.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.apilog.'.$id, function() use ($id)
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
		// Create a new apilog
		$apilog = $this->createModel();

		// Fire the 'sanatorium.hoofmanager.apilog.creating' event
		if ($this->fireEvent('sanatorium.hoofmanager.apilog.creating', [ $input ]) === false)
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
			// Save the apilog
			$apilog->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.apilog.created' event
			$this->fireEvent('sanatorium.hoofmanager.apilog.created', [ $apilog ]);
		}

		return [ $messages, $apilog ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the apilog object
		$apilog = $this->find($id);

		// Fire the 'sanatorium.hoofmanager.apilog.updating' event
		if ($this->fireEvent('sanatorium.hoofmanager.apilog.updating', [ $apilog, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($apilog, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the apilog
			$apilog->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.apilog.updated' event
			$this->fireEvent('sanatorium.hoofmanager.apilog.updated', [ $apilog ]);
		}

		return [ $messages, $apilog ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the apilog exists
		if ($apilog = $this->find($id))
		{
			// Fire the 'sanatorium.hoofmanager.apilog.deleted' event
			$this->fireEvent('sanatorium.hoofmanager.apilog.deleted', [ $apilog ]);

			// Delete the apilog entry
			$apilog->delete();

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
