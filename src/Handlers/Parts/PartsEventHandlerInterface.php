<?php namespace Sanatorium\Hoofmanager\Handlers\Parts;

use Sanatorium\Hoofmanager\Models\Part;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface PartsEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a parts is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a parts is created.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Parts  $parts
	 * @return mixed
	 */
	public function created(Part $parts);

	/**
	 * When a parts is being updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Parts  $parts
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Part $parts, array $data);

	/**
	 * When a parts is updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Parts  $parts
	 * @return mixed
	 */
	public function updated(Part $parts);

	/**
	 * When a parts is deleted.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Parts  $parts
	 * @return mixed
	 */
	public function deleted(Part $parts);

}
