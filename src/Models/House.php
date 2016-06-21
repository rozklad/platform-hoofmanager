<?php namespace Sanatorium\Hoofmanager\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;

class House extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'houses';

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
		'items'
	];

	protected $appends = ['label'];

	/**
	 * {@inheritDoc}
	 */
	protected static $entityNamespace = 'sanatorium/hoofmanager.houses';

	public function items()
	{
		return $this->belongsToMany('Sanatorium\Hoofmanager\Models\Item', 'house_items', 'house_id', 'item_id');
	}

	public function getLabelAttribute()
	{
		return sprintf('%s, %s, %s', $this->company_name, $this->address_line_1, $this->address_line_2);
	}


}
