<?php namespace Sanatorium\Hoofmanager\Handlers\Houses;

use Illuminate\Events\Dispatcher;
use Sanatorium\Hoofmanager\Models\House;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class HousesEventHandler extends BaseEventHandler implements HousesEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.hoofmanager.houses.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.hoofmanager.houses.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.hoofmanager.houses.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.hoofmanager.houses.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.hoofmanager.houses.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(House $houses)
	{
		$this->flushCache($houses);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(House $houses, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(House $houses)
	{
		$this->flushCache($houses);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(House $houses)
	{
		$this->flushCache($houses);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Houses  $houses
	 * @return void
	 */
	protected function flushCache(House $houses)
	{
		$this->app['cache']->forget('sanatorium.hoofmanager.houses.all');

		$this->app['cache']->forget('sanatorium.hoofmanager.houses.'.$houses->id);
	}

}
