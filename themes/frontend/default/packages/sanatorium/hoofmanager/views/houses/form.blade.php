@extends('layouts/default')

@section('sidenav')
@parent
@include('sanatorium/hoofmanager::partials/sidenav')
@stop

{{-- Inline styles --}}
@section('styles')
@parent
<style type="text/css">

	.card-row {

		display: block;

	}

</style>
@stop

{{-- Page content --}}
@section('page')

</div>

</div>

<div class="row">
	
	<form method="POST">

		<h2 class="card-header">
			<a href="{{ route('sanatorium.hoofmanager.front') }}"><i class="ion-ios-arrow-thin-left"></i>	</a>

			@if ( $house->exists() )
			{{ $house->company_name }}
			@else
			Vytvořit chov
			@endif
		</h2>

		<fieldset class="col-md-12">
			<div class="form-group">
				<label for="company_name" class="control-label">Název chovu</label>
				<input type="text" id="company_name" class="form-control" name="company_name" value="{{ $house->company_name }}">
			</div>
			<div class="form-group">
				<label for="cattle_number" class="control-label">ID chovu</label>
				<input type="text" id="cattle_number" class="form-control" name="cattle_number" value="{{ $house->cattle_number }}">
			</div>
			<div class="form-group">
				<label for="address_line_1" class="control-label">Adresa řádek 1</label>
				<input type="text" id="address_line_1" class="form-control" name="address_line_1" value="{{ $house->address_line_1 }}">
			</div>
			<div class="form-group">
				<label for="address_line_2" class="control-label">Adresa řádek 2</label>
				<input type="text" id="address_line_2" class="form-control" name="address_line_2" value="{{ $house->address_line_2 }}">
			</div>
		</fieldset>

		<fieldset class="col-md-12">
			<div class="form-group">
				<button type="submit" class="btn btn-success" style="width:100%;">
					{{ trans('action.save') }}
				</button>
			</div>
		</fieldset>

	</form>

	<div class="col-md-12">

		<h2>

		Zvířata

		</h2>

		@if ( count($house->items) > 0 )

		@foreach ( $house->items as $item )

		<a href="{{ route('sanatorium.hoofmanager.items.edit', ['id' => $item->id]) }}" class="card-row">

			Číslo: # {{ $item->item_number }}

		</a>

		<hr>

		@endforeach

		@else

		Nejsou žádné

		@endif

	</div>

</div>

@stop