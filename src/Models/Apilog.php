<?php namespace Sanatorium\Hoofmanager\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;

class Apilog extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'apilogs';

	/**
	 * {@inheritDoc}
	 */
	protected $guarded = [
	'id',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $with = [
	'values.attribute',
	];

	/**
	 * {@inheritDoc}
	 */
	protected static $entityNamespace = 'sanatorium/hoofmanager.apilog';

	public function treatments()
	{
		dd("ahoj");
		return $this->belongsTo('Sanatorium\Hoofmanager\Models\Examination', 'examination_id');
	}

}
