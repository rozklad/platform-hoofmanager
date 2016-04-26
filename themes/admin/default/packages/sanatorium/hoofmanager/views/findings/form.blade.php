@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/hoofmanager::findings/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page content --}}
@section('page')

<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="hoofmanager-form" action="{{ request()->fullUrl() }}" role="form" method="post" data-parsley-validate>

		{{-- Form: CSRF Token --}}
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<header class="panel-heading">

			<nav class="navbar navbar-default navbar-actions">

				<div class="container-fluid">

					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.hoofmanager.findings.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $finding->exists ? $finding->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($finding->exists)
							<li>
								<a href="{{ route('admin.sanatorium.hoofmanager.findings.delete', $finding->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
									<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
								</a>
							</li>
							@endif

							<li>
								<button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}">
									<i class="fa fa-save"></i> <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
								</button>
							</li>

						</ul>

					</div>

				</div>

			</nav>

		</header>

		<div class="panel-body">

			<div role="tabpanel">

				{{-- Form: Tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/hoofmanager::findings/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/hoofmanager::findings/common.tabs.attributes') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('disease_id', ' has-error') }}">

									<label for="disease_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/hoofmanager::findings/model.general.disease_id_help') }}}"></i>
										{{{ trans('sanatorium/hoofmanager::findings/model.general.disease_id') }}}
									</label>

									<input type="text" class="form-control" name="disease_id" id="disease_id" placeholder="{{{ trans('sanatorium/hoofmanager::findings/model.general.disease_id') }}}" value="{{{ input()->old('disease_id', $finding->disease_id) }}}">

									<span class="help-block">{{{ Alert::onForm('disease_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('part_id', ' has-error') }}">

									<label for="part_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/hoofmanager::findings/model.general.part_id_help') }}}"></i>
										{{{ trans('sanatorium/hoofmanager::findings/model.general.part_id') }}}
									</label>

									<input type="text" class="form-control" name="part_id" id="part_id" placeholder="{{{ trans('sanatorium/hoofmanager::findings/model.general.part_id') }}}" value="{{{ input()->old('part_id', $finding->part_id) }}}">

									<span class="help-block">{{{ Alert::onForm('part_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('subpart_id', ' has-error') }}">

									<label for="subpart_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/hoofmanager::findings/model.general.subpart_id_help') }}}"></i>
										{{{ trans('sanatorium/hoofmanager::findings/model.general.subpart_id') }}}
									</label>

									<input type="text" class="form-control" name="subpart_id" id="subpart_id" placeholder="{{{ trans('sanatorium/hoofmanager::findings/model.general.subpart_id') }}}" value="{{{ input()->old('subpart_id', $finding->subpart_id) }}}">

									<span class="help-block">{{{ Alert::onForm('subpart_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('examination_id', ' has-error') }}">

									<label for="examination_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/hoofmanager::findings/model.general.examination_id_help') }}}"></i>
										{{{ trans('sanatorium/hoofmanager::findings/model.general.examination_id') }}}
									</label>

									<input type="text" class="form-control" name="examination_id" id="examination_id" placeholder="{{{ trans('sanatorium/hoofmanager::findings/model.general.examination_id') }}}" value="{{{ input()->old('examination_id', $finding->examination_id) }}}">

									<span class="help-block">{{{ Alert::onForm('examination_id') }}}</span>

								</div>


							</div>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($finding)
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
