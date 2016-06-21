@extends('layouts/default_sidebar')

@section('sidebar')
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

	.buttons-wrapper span {
		margin: 10px 0 20px 0;
	}

</style>
@stop

{{-- Page content --}}
@section('page')

<div class="row">
	
	<form method="POST">

		<h2 class="card-header">
			<a href="{{ route('sanatorium.hoofmanager.front') }}"><i class="ion-ios-arrow-thin-left"></i>	</a>

			@if ( isset($house) )
			{{ $house->company_name }}
			@else
			Vytvořit chov
			@endif
		</h2>

		<fieldset class="col-md-12">

			@if ( !isset($house) )

			<input type="text" id="user_id" name="house[user_id]" value="{{ $vet->id }}" hidden>

			@endif

			<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

			<div class="form-group">
				<label for="company_name" class="control-label">Název chovu</label>
				<input type="text" id="company_name" class="form-control" name="house[company_name]" value="<?php echo isset($house) ? $house->company_name : ' ' ;?>">
			</div>
			<div class="form-group">
				<label for="cattle_number" class="control-label">ID chovu</label>
				<input type="text" id="cattle_number" class="form-control" name="house[cattle_number]" value="<?php echo isset($house) ? $house->cattle_number : ' ' ;?>">
			</div>
			<div class="form-group">
				<label for="address_line_1" class="control-label">Adresa řádek 1</label>
				<input type="text" id="address_line_1" class="form-control" name="house[address_line_1]" value="<?php echo isset($house) ? $house->address_line_1 : ' ' ;?>">
			</div>
			<div class="form-group">
				<label for="address_line_2" class="control-label">Adresa řádek 2</label>
				<input type="text" id="address_line_2" class="form-control" name="house[address_line_2]" value="<?php echo isset($house) ? $house->address_line_2 : ' ' ?>">
			</div>
		</fieldset>

		<div class="col-md-12">

			<h2>

				Zvířata

			</h2>

			@if ( isset($house) )

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

			@endif

			<h3>
				
				Nová zvířata

			</h3>

			<div class="form-group inline-form new_items" data-item-count="0">

				<input type="text" id="user_id" name="item[0][user_id]" value="{{ $vet->id }}" hidden>

				<label for="item_number">Číslo zvířete</label>

				# <input type="text" id="item_number" name="item[0][item_number]" class="form-control" require>

			</div>

		</div>

		<fieldset class="col-md-12">
			<div class="form-group buttons-wrapper">

				<span class="btn btn-primary btn-block" id="more_items">Přidat další</span>

				<button type="submit" class="btn btn-success" style="width:100%;">

					{{ trans('action.save') }}

				</button>
			</div>
		</fieldset>

	</form>

</div>

@stop

@section('scripts')

<script>
	
	$(function(){

		var itemCount = 1;

		$('#more_items').on('click', function(){

			$('.new_items').last().after('<div class="form-group inline-form new_items" data-item-count="' + itemCount + '"><input type="text" id="user_id" name="item[' + itemCount + '][user_id]" value="{{ $vet->id }}" hidden><label for="item_number">Číslo zvířete</label># <input type="text" id="item_number" name="item[' + itemCount + '][item_number]" class="form-control" require></div>');

			itemCount++;

		})

	})

</script>

@stop