@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{ trans('sanatorium/hoofmanager::chapters/common.title') }}
@stop

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
<style type="text/css">
	pre {
		margin-top: 0.5em;
	}
</style>
@parent
@stop

{{-- Page content --}}
@section('page')

@foreach( $calls as $name => $call )

<section class="panel panel-default panel-grid">

	<header class="panel-heading" name="{{ $name }}" id="{{ $name }}">

		<code>{{ $call['method'] }}</code> {{ $name }}

	</header>

	<div class="panel-body">
		
		<div class="col-sm-12">
			<p style="padding: 10px 0 0;">
			@if ( isset($call['description']) )
				{{ $call['description'] }}
			@endif
			</p>
			<pre>{{ route($call['route']) }}</pre>
		</div>

		<div class="col-sm-12">

		@if ( isset($call['request']) )
			<h4>Request</h4>
			<pre>{{ $call['request'] }}</pre>
		@endif
		
		@if ( isset($call['response']) )
			@foreach( $call['response'] as $type => $response )
				<h4>Response [{{ $response['status'] }}]</h4>
				<pre>{{ $response['content'] }}</pre>
			@endforeach
		@endif

		</div>
	</div>

</section>

@endforeach

@if (config('platform.app.help'))
	@include('sanatorium/hoofmanager::help')
@endif

@stop
