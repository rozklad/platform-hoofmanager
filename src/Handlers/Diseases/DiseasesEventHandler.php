<?php namespace Sanatorium\Hoofmanager\Handlers\Diseases;

use Illuminate\Events\Dispatcher;
use Sanatorium\Hoofmanager\Models\Disease;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class DiseasesEventHandler extends BaseEventHandler implements DiseasesEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.hoofmanager.diseases.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.hoofmanager.diseases.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.hoofmanager.diseases.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.hoofmanager.diseases.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.hoofmanager.diseases.deleted', __CLASS__.'@deleted');
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
	public function created(Disease $diseases)
	{
		$this->flushCache($diseases);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Disease $diseases, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Disease $diseases)
	{
		$this->flushCache($diseases);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Disease $diseases)
	{
		$this->flushCache($diseases);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Diseases  $diseases
	 * @return void
	 */
	protected function flushCache(Disease $diseases)
	{
		$this->app['cache']->forget('sanatorium.hoofmanager.diseases.all');

		$this->app['cache']->forget('sanatorium.hoofmanager.diseases.'.$diseases->id);
	}

}
