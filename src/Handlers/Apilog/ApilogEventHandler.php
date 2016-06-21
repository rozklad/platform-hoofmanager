<?php namespace Sanatorium\Hoofmanager\Handlers\Apilog;

use Illuminate\Events\Dispatcher;
use Sanatorium\Hoofmanager\Models\Apilog;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class ApilogEventHandler extends BaseEventHandler implements ApilogEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.hoofmanager.apilog.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.hoofmanager.apilog.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.hoofmanager.apilog.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.hoofmanager.apilog.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.hoofmanager.apilog.deleted', __CLASS__.'@deleted');
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
	public function created(Apilog $apilog)
	{
		$this->flushCache($apilog);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Apilog $apilog, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Apilog $apilog)
	{
		$this->flushCache($apilog);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Apilog $apilog)
	{
		$this->flushCache($apilog);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Apilog  $apilog
	 * @return void
	 */
	protected function flushCache(Apilog $apilog)
	{
		$this->app['cache']->forget('sanatorium.hoofmanager.apilog.all');

		$this->app['cache']->forget('sanatorium.hoofmanager.apilog.'.$apilog->id);
	}

}
