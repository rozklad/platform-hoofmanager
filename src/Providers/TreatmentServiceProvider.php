<?php namespace Sanatorium\Hoofmanager\Providers;

use Cartalyst\Support\ServiceProvider;

class TreatmentServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Hoofmanager\Models\Treatment']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.hoofmanager.treatment.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.hoofmanager.treatment', 'Sanatorium\Hoofmanager\Repositories\Treatment\TreatmentRepository');

		// Register the data handler
		$this->bindIf('sanatorium.hoofmanager.treatment.handler.data', 'Sanatorium\Hoofmanager\Handlers\Treatment\TreatmentDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.hoofmanager.treatment.handler.event', 'Sanatorium\Hoofmanager\Handlers\Treatment\TreatmentEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.hoofmanager.treatment.validator', 'Sanatorium\Hoofmanager\Validator\Treatment\TreatmentValidator');
	}

}
