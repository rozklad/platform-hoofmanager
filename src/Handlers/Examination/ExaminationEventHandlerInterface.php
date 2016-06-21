<?php namespace Sanatorium\Hoofmanager\Handlers\Examination;

use Sanatorium\Hoofmanager\Models\Examination;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface ExaminationEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a examination is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a examination is created.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Examination  $examination
	 * @return mixed
	 */
	public function created(Examination $examination);

	/**
	 * When a examination is being updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Examination  $examination
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Examination $examination, array $data);

	/**
	 * When a examination is updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Examination  $examination
	 * @return mixed
	 */
	public function updated(Examination $examination);

	/**
	 * When a examination is deleted.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Examination  $examination
	 * @return mixed
	 */
	public function deleted(Examination $examination);

}
