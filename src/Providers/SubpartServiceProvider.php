<?php namespace Sanatorium\Hoofmanager\Providers;

use Cartalyst\Support\ServiceProvider;

class SubpartServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Hoofmanager\Models\Subpart']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.hoofmanager.subpart.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.hoofmanager.subpart', 'Sanatorium\Hoofmanager\Repositories\Subpart\SubpartRepository');

		// Register the data handler
		$this->bindIf('sanatorium.hoofmanager.subpart.handler.data', 'Sanatorium\Hoofmanager\Handlers\Subpart\SubpartDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.hoofmanager.subpart.handler.event', 'Sanatorium\Hoofmanager\Handlers\Subpart\SubpartEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.hoofmanager.subpart.validator', 'Sanatorium\Hoofmanager\Validator\Subpart\SubpartValidator');
	}

}
