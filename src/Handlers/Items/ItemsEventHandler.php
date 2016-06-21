<?php namespace Sanatorium\Hoofmanager\Handlers\Items;

use Illuminate\Events\Dispatcher;
use Sanatorium\Hoofmanager\Models\Item;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class ItemsEventHandler extends BaseEventHandler implements ItemsEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.hoofmanager.items.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.hoofmanager.items.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.hoofmanager.items.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.hoofmanager.items.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.hoofmanager.items.deleted', __CLASS__.'@deleted');
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
	public function created(Item $items)
	{
		$this->flushCache($items);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Item $items, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Item $items)
	{
		$this->flushCache($items);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Item $items)
	{
		$this->flushCache($items);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Items  $items
	 * @return void
	 */
	protected function flushCache(Item $items)
	{
		$this->app['cache']->forget('sanatorium.hoofmanager.items.all');

		$this->app['cache']->forget('sanatorium.hoofmanager.items.'.$items->id);
	}

}
