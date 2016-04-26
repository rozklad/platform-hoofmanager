<?php namespace Sanatorium\Hoofmanager\Providers;

use Cartalyst\Support\ServiceProvider;

class ItemsServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Hoofmanager\Models\Item']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.hoofmanager.items.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.hoofmanager.items', 'Sanatorium\Hoofmanager\Repositories\Items\ItemsRepository');

		// Register the data handler
		$this->bindIf('sanatorium.hoofmanager.items.handler.data', 'Sanatorium\Hoofmanager\Handlers\Items\ItemsDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.hoofmanager.items.handler.event', 'Sanatorium\Hoofmanager\Handlers\Items\ItemsEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.hoofmanager.items.validator', 'Sanatorium\Hoofmanager\Validator\Items\ItemsValidator');
	}

}
