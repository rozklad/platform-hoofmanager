<?php namespace Sanatorium\Hoofmanager\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;

class Examination extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'examinations';

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
		'item',
		'findings',
	];

	/**
	 * {@inheritDoc}
	 */
	protected static $entityNamespace = 'sanatorium/hoofmanager.examination';

	public function item()
	{
		return $this->belongsTo('Sanatorium\Hoofmanager\Models\Item', 'item_id');
	}

	public function findings()
	{
		return $this->hasMany('Sanatorium\Hoofmanager\Models\Finding', 'examination_id');
	}

}
