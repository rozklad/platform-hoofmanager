<?php namespace Sanatorium\Hoofmanager\Handlers\Houses;

use Sanatorium\Hoofmanager\Models\House;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface HousesEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a houses is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a houses is created.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Houses  $houses
	 * @return mixed
	 */
	public function created(House $houses);

	/**
	 * When a houses is being updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Houses  $houses
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(House $houses, array $data);

	/**
	 * When a houses is updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Houses  $houses
	 * @return mixed
	 */
	public function updated(House $houses);

	/**
	 * When a houses is deleted.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Houses  $houses
	 * @return mixed
	 */
	public function deleted(House $houses);

}
