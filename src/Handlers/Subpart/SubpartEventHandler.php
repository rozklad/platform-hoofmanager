<?php namespace Sanatorium\Hoofmanager\Handlers\Subpart;

use Illuminate\Events\Dispatcher;
use Sanatorium\Hoofmanager\Models\Subpart;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class SubpartEventHandler extends BaseEventHandler implements SubpartEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.hoofmanager.subpart.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.hoofmanager.subpart.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.hoofmanager.subpart.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.hoofmanager.subpart.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.hoofmanager.subpart.deleted', __CLASS__.'@deleted');
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
	public function created(Subpart $subpart)
	{
		$this->flushCache($subpart);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Subpart $subpart, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Subpart $subpart)
	{
		$this->flushCache($subpart);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Subpart $subpart)
	{
		$this->flushCache($subpart);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Subpart  $subpart
	 * @return void
	 */
	protected function flushCache(Subpart $subpart)
	{
		$this->app['cache']->forget('sanatorium.hoofmanager.subpart.all');

		$this->app['cache']->forget('sanatorium.hoofmanager.subpart.'.$subpart->id);
	}

}
