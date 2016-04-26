<?php namespace Sanatorium\Hoofmanager\Handlers\Items;

use Sanatorium\Hoofmanager\Models\Item;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface ItemsEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a items is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a items is created.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Items  $items
	 * @return mixed
	 */
	public function created(Item $items);

	/**
	 * When a items is being updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Items  $items
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Item $items, array $data);

	/**
	 * When a items is updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Items  $items
	 * @return mixed
	 */
	public function updated(Item $items);

	/**
	 * When a items is deleted.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Items  $items
	 * @return mixed
	 */
	public function deleted(Item $items);

}
