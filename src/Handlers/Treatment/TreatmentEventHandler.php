<?php namespace Sanatorium\Hoofmanager\Handlers\Treatment;

use Illuminate\Events\Dispatcher;
use Sanatorium\Hoofmanager\Models\Treatment;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class TreatmentEventHandler extends BaseEventHandler implements TreatmentEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.hoofmanager.treatment.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.hoofmanager.treatment.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.hoofmanager.treatment.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.hoofmanager.treatment.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.hoofmanager.treatment.deleted', __CLASS__.'@deleted');
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
	public function created(Treatment $treatment)
	{
		$this->flushCache($treatment);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Treatment $treatment, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Treatment $treatment)
	{
		$this->flushCache($treatment);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Treatment $treatment)
	{
		$this->flushCache($treatment);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Treatment  $treatment
	 * @return void
	 */
	protected function flushCache(Treatment $treatment)
	{
		$this->app['cache']->forget('sanatorium.hoofmanager.treatment.all');

		$this->app['cache']->forget('sanatorium.hoofmanager.treatment.'.$treatment->id);
	}

}
