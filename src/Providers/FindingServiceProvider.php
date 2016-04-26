<?php namespace Sanatorium\Hoofmanager\Providers;

use Cartalyst\Support\ServiceProvider;

class FindingServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Hoofmanager\Models\Finding']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.hoofmanager.finding.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.hoofmanager.finding', 'Sanatorium\Hoofmanager\Repositories\Finding\FindingRepository');

		// Register the data handler
		$this->bindIf('sanatorium.hoofmanager.finding.handler.data', 'Sanatorium\Hoofmanager\Handlers\Finding\FindingDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.hoofmanager.finding.handler.event', 'Sanatorium\Hoofmanager\Handlers\Finding\FindingEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.hoofmanager.finding.validator', 'Sanatorium\Hoofmanager\Validator\Finding\FindingValidator');
	}

}
