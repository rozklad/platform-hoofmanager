<?php namespace Sanatorium\Hoofmanager\Handlers\Apilog;

use Sanatorium\Hoofmanager\Models\Apilog;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface ApilogEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a apilog is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a apilog is created.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Apilog  $apilog
	 * @return mixed
	 */
	public function created(Apilog $apilog);

	/**
	 * When a apilog is being updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Apilog  $apilog
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Apilog $apilog, array $data);

	/**
	 * When a apilog is updated.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Apilog  $apilog
	 * @return mixed
	 */
	public function updated(Apilog $apilog);

	/**
	 * When a apilog is deleted.
	 *
	 * @param  \Sanatorium\Hoofmanager\Models\Apilog  $apilog
	 * @return mixed
	 */
	public function deleted(Apilog $apilog);

}
