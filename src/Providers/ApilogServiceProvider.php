<?php namespace Sanatorium\Hoofmanager\Providers;

use Cartalyst\Support\ServiceProvider;

class ApilogServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Hoofmanager\Models\Apilog']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.hoofmanager.apilog.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.hoofmanager.apilog', 'Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepository');

		// Register the data handler
		$this->bindIf('sanatorium.hoofmanager.apilog.handler.data', 'Sanatorium\Hoofmanager\Handlers\Apilog\ApilogDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.hoofmanager.apilog.handler.event', 'Sanatorium\Hoofmanager\Handlers\Apilog\ApilogEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.hoofmanager.apilog.validator', 'Sanatorium\Hoofmanager\Validator\Apilog\ApilogValidator');
	}

}
