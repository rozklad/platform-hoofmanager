<?php namespace Sanatorium\Hoofmanager\Handlers\Subpart;

use Sanatorium\Hoofmanager\Models\Subpart;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface SubpartEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a subpart is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a subpart is created.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Subpart  $subpart
	 * @return mixed
	 */
	public function created(Subpart $subpart);

	/**
	 * When a subpart is being updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Subpart  $subpart
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Subpart $subpart, array $data);

	/**
	 * When a subpart is updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Subpart  $subpart
	 * @return mixed
	 */
	public function updated(Subpart $subpart);

	/**
	 * When a subpart is deleted.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Subpart  $subpart
	 * @return mixed
	 */
	public function deleted(Subpart $subpart);

}
