<?php namespace Sanatorium\Hoofmanager\Repositories\Diseases;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class DiseasesRepository implements DiseasesRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Hoofmanager\Handlers\Diseases\DiseasesDataHandlerInterface
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

		$this->data = $app['sanatorium.hoofmanager.diseases.handler.data'];

		$this->setValidator($app['sanatorium.hoofmanager.diseases.validator']);

		$this->setModel(get_class($app['Sanatorium\Hoofmanager\Models\Disease']));
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
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.diseases.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.hoofmanager.diseases.'.$id, function() use ($id)
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
		// Create a new diseases
		$diseases = $this->createModel();

		// Fire the 'sanatorium.hoofmanager.diseases.creating' event
		if ($this->fireEvent('sanatorium.hoofmanager.diseases.creating', [ $input ]) === false)
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
			// Save the diseases
			$diseases->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.diseases.created' event
			$this->fireEvent('sanatorium.hoofmanager.diseases.created', [ $diseases ]);
		}

		return [ $messages, $diseases ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the diseases object
		$diseases = $this->find($id);

		// Fire the 'sanatorium.hoofmanager.diseases.updating' event
		if ($this->fireEvent('sanatorium.hoofmanager.diseases.updating', [ $diseases, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($diseases, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the diseases
			$diseases->fill($data)->save();

			// Fire the 'sanatorium.hoofmanager.diseases.updated' event
			$this->fireEvent('sanatorium.hoofmanager.diseases.updated', [ $diseases ]);
		}

		return [ $messages, $diseases ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the diseases exists
		if ($diseases = $this->find($id))
		{
			// Fire the 'sanatorium.hoofmanager.diseases.deleted' event
			$this->fireEvent('sanatorium.hoofmanager.diseases.deleted', [ $diseases ]);

			// Delete the diseases entry
			$diseases->delete();

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
