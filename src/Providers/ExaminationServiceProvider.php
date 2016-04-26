<?php namespace Sanatorium\Hoofmanager\Providers;

use Cartalyst\Support\ServiceProvider;

class ExaminationServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Hoofmanager\Models\Examination']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.hoofmanager.examination.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.hoofmanager.examination', 'Sanatorium\Hoofmanager\Repositories\Examination\ExaminationRepository');

		// Register the data handler
		$this->bindIf('sanatorium.hoofmanager.examination.handler.data', 'Sanatorium\Hoofmanager\Handlers\Examination\ExaminationDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.hoofmanager.examination.handler.event', 'Sanatorium\Hoofmanager\Handlers\Examination\ExaminationEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.hoofmanager.examination.validator', 'Sanatorium\Hoofmanager\Validator\Examination\ExaminationValidator');
	}

}
