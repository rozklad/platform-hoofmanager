<?php namespace Sanatorium\Hoofmanager\Handlers\Finding;

use Illuminate\Events\Dispatcher;
use Sanatorium\Hoofmanager\Models\Finding;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class FindingEventHandler extends BaseEventHandler implements FindingEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.hoofmanager.finding.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.hoofmanager.finding.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.hoofmanager.finding.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.hoofmanager.finding.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.hoofmanager.finding.deleted', __CLASS__.'@deleted');
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
	public function created(Finding $finding)
	{
		$this->flushCache($finding);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Finding $finding, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Finding $finding)
	{
		$this->flushCache($finding);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Finding $finding)
	{
		$this->flushCache($finding);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Finding  $finding
	 * @return void
	 */
	protected function flushCache(Finding $finding)
	{
		$this->app['cache']->forget('sanatorium.hoofmanager.finding.all');

		$this->app['cache']->forget('sanatorium.hoofmanager.finding.'.$finding->id);
	}

}
