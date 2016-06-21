<?php namespace Sanatorium\Hoofmanager\Repositories\Examination;

interface ExaminationRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Sanatorium\Hoofmanager\Models\Examination
	 */
	public function grid();

	/**
	 * Returns all the hoofmanager entries.
	 *
	 * @return \Sanatorium\Hoofmanager\Models\Examination
	 */
	public function findAll();

	/**
	 * Returns a hoofmanager entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Sanatorium\Hoofmanager\Models\Examination
	 */
	public function find($id);

	/**
	 * Determines if the given hoofmanager is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given hoofmanager is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given hoofmanager.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a hoofmanager entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Sanatorium\Hoofmanager\Models\Examination
	 */
	public function create(array $data);

	/**
	 * Updates the hoofmanager entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Sanatorium\Hoofmanager\Models\Examination
	 */
	public function update($id, array $data);

	/**
	 * Deletes the hoofmanager entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
