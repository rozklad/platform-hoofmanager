<?php namespace Sanatorium\Hoofmanager\Providers;

use Cartalyst\Support\ServiceProvider;

class PartsServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Hoofmanager\Models\Part']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.hoofmanager.parts.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.hoofmanager.parts', 'Sanatorium\Hoofmanager\Repositories\Parts\PartsRepository');

		// Register the data handler
		$this->bindIf('sanatorium.hoofmanager.parts.handler.data', 'Sanatorium\Hoofmanager\Handlers\Parts\PartsDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.hoofmanager.parts.handler.event', 'Sanatorium\Hoofmanager\Handlers\Parts\PartsEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.hoofmanager.parts.validator', 'Sanatorium\Hoofmanager\Validator\Parts\PartsValidator');
	}

}
