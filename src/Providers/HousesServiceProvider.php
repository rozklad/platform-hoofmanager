<?php namespace Sanatorium\Hoofmanager\Providers;

use Cartalyst\Support\ServiceProvider;

class HousesServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Hoofmanager\Models\House']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.hoofmanager.houses.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.hoofmanager.houses', 'Sanatorium\Hoofmanager\Repositories\Houses\HousesRepository');

		// Register the data handler
		$this->bindIf('sanatorium.hoofmanager.houses.handler.data', 'Sanatorium\Hoofmanager\Handlers\Houses\HousesDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.hoofmanager.houses.handler.event', 'Sanatorium\Hoofmanager\Handlers\Houses\HousesEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.hoofmanager.houses.validator', 'Sanatorium\Hoofmanager\Validator\Houses\HousesValidator');
	}

}
