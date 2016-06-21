@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/hoofmanager::examinations/common.title') }}
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

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.hoofmanager.examinations.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $examination->exists ? $examination->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($examination->exists)
							<li>
								<a href="{{ route('admin.sanatorium.hoofmanager.examinations.delete', $examination->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
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
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/hoofmanager::examinations/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/hoofmanager::examinations/common.tabs.attributes') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('user_id', ' has-error') }}">

									<label for="user_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/hoofmanager::examinations/model.general.user_id_help') }}}"></i>
										{{{ trans('sanatorium/hoofmanager::examinations/model.general.user_id') }}}
									</label>

									<input type="text" class="form-control" name="user_id" id="user_id" placeholder="{{{ trans('sanatorium/hoofmanager::examinations/model.general.user_id') }}}" value="{{{ input()->old('user_id', $examination->user_id) }}}">

									<span class="help-block">{{{ Alert::onForm('user_id') }}}</span>

								</div>

							</div>

							<table class="table">
								<thead>
									<th>Dobytek</th>
									<th>Nemoc</th>
									<th>Část</th>
									<th>Členění</th>
									<th>Chov</th>
									<th>Akce</th>
								</thead>
								<tbody>
							@foreach( $examination->findings as $finding )
									<tr>
										<td>
											@if ( is_object($examination->item) )
											{{ $examination->item->item_number }}
											<a href="{{ route('admin.sanatorium.hoofmanager.items.edit', $examination->item->id) }}">
												<i class="fa fa-edit"></i>
											</a>
											( {{ $examination->item->collar }} )
											@endif
										</td>
										<td>
											{{ $finding->type }}:
											
											@if ( is_object($examination->disease) )
											{{ $finding->disease->name }}
											<a href="{{ route('admin.sanatorium.hoofmanager.diseases.edit', $finding->disease->id) }}">
												<i class="fa fa-edit"></i>
											</a>
											@endif
										</td>
										<td>
										<?php $part = $finding->part()->first() ?>
										@if ( is_object($part) )
											{{ $part->name }}
											<a href="{{ route('admin.sanatorium.hoofmanager.parts.edit', $part->id) }}">
												<i class="fa fa-edit"></i>
											</a>
										@else
											{{ $finding->part_id }}
										@endif
										</td>
										<td>
										<?php $subpart = $finding->subpart()->first() ?>
										@if ( is_object($subpart) )
											{{ $subpart->label }}
											<a href="{{ route('admin.sanatorium.hoofmanager.subparts.edit', $subpart->id) }}">
												<i class="fa fa-edit"></i>
											</a>
										@else
											{{ $finding->subpart_id }}
										@endif
										</td>
										<td>
											<?php $house = $examination->item->houses()->first(); ?>
											{{ $house->label }}
											<a href="{{ route('admin.sanatorium.hoofmanager.houses.edit', $house->id) }}">
												<i class="fa fa-edit"></i>
											</a>
										</td>
										<td class="text-right">
											<a href="#">
												<i class="fa fa-save"></i>
											</a>
											<a href="{{ route('admin.sanatorium.hoofmanager.findings.delete', $finding->id) }}">
												<i class="fa fa-trash-o"></i>
											</a>
										</td>
									</tr>
							@endforeach
								</tbody>
							</table>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($examination)
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
