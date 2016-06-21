<?php namespace Sanatorium\Hoofmanager\Handlers\Diseases;

use Sanatorium\Hoofmanager\Models\Disease;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface DiseasesEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a diseases is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a diseases is created.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Diseases  $diseases
	 * @return mixed
	 */
	public function created(Disease $diseases);

	/**
	 * When a diseases is being updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Diseases  $diseases
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Disease $diseases, array $data);

	/**
	 * When a diseases is updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Diseases  $diseases
	 * @return mixed
	 */
	public function updated(Disease $diseases);

	/**
	 * When a diseases is deleted.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Diseases  $diseases
	 * @return mixed
	 */
	public function deleted(Disease $diseases);

}
