<?php namespace Sanatorium\Hoofmanager\Handlers\Treatment;

use Sanatorium\Hoofmanager\Models\Treatment;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface TreatmentEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a treatment is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a treatment is created.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Treatment  $treatment
	 * @return mixed
	 */
	public function created(Treatment $treatment);

	/**
	 * When a treatment is being updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Treatment  $treatment
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Treatment $treatment, array $data);

	/**
	 * When a treatment is updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Treatment  $treatment
	 * @return mixed
	 */
	public function updated(Treatment $treatment);

	/**
	 * When a treatment is deleted.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Treatment  $treatment
	 * @return mixed
	 */
	public function deleted(Treatment $treatment);

}
