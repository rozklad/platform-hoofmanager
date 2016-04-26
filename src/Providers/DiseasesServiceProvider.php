<?php namespace Sanatorium\Hoofmanager\Providers;

use Cartalyst\Support\ServiceProvider;

class DiseasesServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Hoofmanager\Models\Disease']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.hoofmanager.diseases.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.hoofmanager.diseases', 'Sanatorium\Hoofmanager\Repositories\Diseases\DiseasesRepository');

		// Register the data handler
		$this->bindIf('sanatorium.hoofmanager.diseases.handler.data', 'Sanatorium\Hoofmanager\Handlers\Diseases\DiseasesDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.hoofmanager.diseases.handler.event', 'Sanatorium\Hoofmanager\Handlers\Diseases\DiseasesEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.hoofmanager.diseases.validator', 'Sanatorium\Hoofmanager\Validator\Diseases\DiseasesValidator');
	}

}
