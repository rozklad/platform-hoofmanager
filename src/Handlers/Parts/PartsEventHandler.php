<?php namespace Sanatorium\Hoofmanager\Handlers\Parts;

use Illuminate\Events\Dispatcher;
use Sanatorium\Hoofmanager\Models\Part;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class PartsEventHandler extends BaseEventHandler implements PartsEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.hoofmanager.parts.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.hoofmanager.parts.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.hoofmanager.parts.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.hoofmanager.parts.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.hoofmanager.parts.deleted', __CLASS__.'@deleted');
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
	public function created(Part $parts)
	{
		$this->flushCache($parts);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Part $parts, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Part $parts)
	{
		$this->flushCache($parts);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Part $parts)
	{
		$this->flushCache($parts);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Parts  $parts
	 * @return void
	 */
	protected function flushCache(Part $parts)
	{
		$this->app['cache']->forget('sanatorium.hoofmanager.parts.all');

		$this->app['cache']->forget('sanatorium.hoofmanager.parts.'.$parts->id);
	}

}
