<?php namespace Sanatorium\Hoofmanager\Handlers\Examination;

use Illuminate\Events\Dispatcher;
use Sanatorium\Hoofmanager\Models\Examination;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class ExaminationEventHandler extends BaseEventHandler implements ExaminationEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.hoofmanager.examination.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.hoofmanager.examination.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.hoofmanager.examination.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.hoofmanager.examination.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.hoofmanager.examination.deleted', __CLASS__.'@deleted');
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
	public function created(Examination $examination)
	{
		$this->flushCache($examination);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Examination $examination, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Examination $examination)
	{
		$this->flushCache($examination);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Examination $examination)
	{
		$this->flushCache($examination);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Examination  $examination
	 * @return void
	 */
	protected function flushCache(Examination $examination)
	{
		$this->app['cache']->forget('sanatorium.hoofmanager.examination.all');

		$this->app['cache']->forget('sanatorium.hoofmanager.examination.'.$examination->id);
	}

}
