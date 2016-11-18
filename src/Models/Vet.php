<?php namespace Sanatorium\Hoofmanager\Models;

use Platform\Users\Models\User as BaseUser;

use Sentinel;

class Vet extends BaseUser {

	protected $with = [
		'values.attribute',
		//'examinations',
	];

	public function examinations()
	{
		return $this->hasMany('Sanatorium\Hoofmanager\Models\Finding', 'user_id');
	}

	public static function getVet()
	{
		if ( !Sentinel::check() )
			return false;

		$user = Sentinel::getUser();

		return Vet::find($user->id); 
	}

	public static function isAdmin()
	{
        if ( !Sentinel::check() )
            return false;

		return Sentinel::hasAnyAccess(['admin.dashboard']);
	}

}
