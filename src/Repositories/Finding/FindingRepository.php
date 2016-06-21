<?php namespace Sanatorium\Hoofmanager\Repositories\Finding;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class FindingRepository implements FindingRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Hoofmanager\Handlers\Finding\FindingDataHandlerInterface
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

		$this->data = $app['sanatorium.hoofmanager.finding.handler.data'];

		$this->setValidator($app['sanatorium.hoofmanager.finding.validator']);

		$this->setModel(get_class($app['Sanatorium\Hoofmanager\Models\Finding']));
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
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.finding.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.finding.'.$id, function() use ($id)
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
		// Create a new finding
		$finding = $this->createModel();

		// Fire the 'sanatorium.hoofmanager.finding.creating' event
		if ($this->fireEvent('sanatorium.hoofmanager.finding.creating', [ $input ]) === false)
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
			// Save the finding
			$finding->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.finding.created' event
			$this->fireEvent('sanatorium.hoofmanager.finding.created', [ $finding ]);
		}

		return [ $messages, $finding ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the finding object
		$finding = $this->find($id);

		// Fire the 'sanatorium.hoofmanager.finding.updating' event
		if ($this->fireEvent('sanatorium.hoofmanager.finding.updating', [ $finding, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($finding, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the finding
			$finding->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.finding.updated' event
			$this->fireEvent('sanatorium.hoofmanager.finding.updated', [ $finding ]);
		}

		return [ $messages, $finding ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the finding exists
		if ($finding = $this->find($id))
		{
			// Fire the 'sanatorium.hoofmanager.finding.deleted' event
			$this->fireEvent('sanatorium.hoofmanager.finding.deleted', [ $finding ]);

			// Delete the finding entry
			$finding->delete();

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
