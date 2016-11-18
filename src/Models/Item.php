<?php namespace Sanatorium\Hoofmanager\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;

class Item extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'items';

	/**
	 * {@inheritDoc}
	 */
	protected $guarded = [
		//'id',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $with = [
		'values.attribute',
		//'houses',
	];

	/**
	 * {@inheritDoc}
	 */
	protected static $entityNamespace = 'sanatorium/hoofmanager.items';

	public function parts()
	{
		return $this->belongsToMany('Sanatorium\Hoofmanager\Models\Part', 'item_parts', 'item_id', 'part_id');
	}

    public function findings()
    {
        return $this->hasMany('Sanatorium\Hoofmanager\Models\Finding', 'item_id');
    }

	public function houses()
	{
		return $this->belongsToMany('Sanatorium\Hoofmanager\Models\House', 'house_items', 'item_id', 'house_id');
	}

}
