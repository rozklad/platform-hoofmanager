<?php namespace Sanatorium\Hoofmanager\Repositories\Houses;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class HousesRepository implements HousesRepositoryInterface {

    use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

    /**
     * The Data handler.
     *
     * @var \Sanatorium\Hoofmanager\Handlers\Houses\HousesDataHandlerInterface
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

        $this->data = $app['sanatorium.hoofmanager.houses.handler.data'];

        $this->setValidator($app['sanatorium.hoofmanager.houses.validator']);

        $this->setModel(get_class($app['Sanatorium\Hoofmanager\Models\House']));
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
        return $this->container['cache']->rememberForever('sanatorium.hoofmanager.houses.all', function()
        {
            return $this->createModel()->get();
        });
    }

    /**
     * {@inheritDoc}
     */
    public function find($id)
    {
        return $this->container['cache']->rememberForever('sanatorium.hoofmanager.houses.'.$id, function() use ($id)
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
        // Create a new houses
        $houses = $this->createModel();

        // Fire the 'sanatorium.hoofmanager.houses.creating' event
        if ($this->fireEvent('sanatorium.hoofmanager.houses.creating', [ $input ]) === false)
        {
            return false;
        }

        if ( isset($input['items']) )
        {
            $items = array_pull($input, 'items');
        } else {
            $items = [];
        }

        // Prepare the submitted data
        $data = $this->data->prepare($input);

        // Validate the submitted data
        $messages = $this->validForCreation($data);

        // Check if the validation returned any errors
        if ($messages->isEmpty())
        {
            // Save the houses
            $houses->fill($data)->save();

            $items_to_save = [];

            foreach( $items as $item )
            {
                $temp_id = array_pull($item, 'id');

                $object = \Sanatorium\Hoofmanager\Models\Item::create($item);

                $items_to_save[] = $object;
            }

            $houses->items()->saveMany($items_to_save);

            // Fire the 'sanatorium.hoofmanager.houses.created' event
            $this->fireEvent('sanatorium.hoofmanager.houses.created', [ $houses ]);
        }

        $houses->load('items');

        return [ $messages, $houses ];
    }

    /**
     * {@inheritDoc}
     */
    public function update($id, array $input)
    {
        // Get the houses object
        $houses = $this->find($id);

        // Fire the 'sanatorium.hoofmanager.houses.updating' event
        if ($this->fireEvent('sanatorium.hoofmanager.houses.updating', [ $houses, $input ]) === false)
        {
            return false;
        }

        // Check if there are items
        if ( isset($input['items']) )
        {
            $items = array_pull($input, 'items');
        } else {
            $items = [];
        }

        // Prepare the submitted data
        $data = $this->data->prepare($input);

        // Validate the submitted data
        $messages = $this->validForUpdate($houses, $data);

        // Check if the validation returned any errors
        if ($messages->isEmpty())
        {
            // Update the houses
            $houses->fill($data)->save();

            $items_to_save = [];

            foreach( $items as $item )
            {
                /*
                       if ( ! isset( $item['id'] ) ) {

                                    $object = \Sanatorium\Hoofmanager\Models\Item::create($item);

                                    $items_to_save[] = $object;

                                }
                */

                list($messages, $object) = app('sanatorium.hoofmanager.items')->store($item['id'], $item);

                $items_to_save[] = $object->id;

            }

            $houses->items()->sync($items_to_save, false);

            // Fire the 'sanatorium.hoofmanager.houses.updated' event
            $this->fireEvent('sanatorium.hoofmanager.houses.updated', [ $houses ]);
        }

        $houses->load('items');

        return [ $messages, $houses ];
    }

    /**
     * {@inheritDoc}
     */
    public function delete($id)
    {
        // Check if the houses exists
        if ($houses = $this->find($id))
        {
            // Fire the 'sanatorium.hoofmanager.houses.deleted' event
            $this->fireEvent('sanatorium.hoofmanager.houses.deleted', [ $houses ]);

            // Delete the houses entry
            $houses->delete();

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
