<?php namespace Sanatorium\Hoofmanager\Handlers\Finding;

use Sanatorium\Hoofmanager\Models\Finding;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface FindingEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a finding is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a finding is created.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Finding  $finding
	 * @return mixed
	 */
	public function created(Finding $finding);

	/**
	 * When a finding is being updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Finding  $finding
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Finding $finding, array $data);

	/**
	 * When a finding is updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Finding  $finding
	 * @return mixed
	 */
	public function updated(Finding $finding);

	/**
	 * When a finding is deleted.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Finding  $finding
	 * @return mixed
	 */
	public function deleted(Finding $finding);

}
