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

	select {
		height: 100%;
		width: 100%;
	}

</style>
@stop

{{-- Page content --}}
@section('page')

</div>

</div>

<div class="row">

	<h2 class="card-header">

		# {{ $item->item_number }} - Patří do chovu: # {{ $house->cattle_number }}, {{ $house->company_name }}

	</h2>

	<div class="col-md-12">

		<span class="card-row">

			<form method="POST">

				<div class="form-group">
					<label for="collar" class="control-label">Obojek</label>
					<input type="text" id="collar" class="form-control" name="collar" value="{{ $item->collar }}">
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-success" style="width:100%;">
						{{ trans('action.save') }}
					</button>
				</div>

			<!--@if ( $item->collar )

			{{ $item->collar }}

			@else

			Aktuálně bez obojku

			@endif-->

		</form>

	</span>

	<h3>

		Nálezy

	</h3>

	<table class="table">
		<thead>
			<th>Typ</th>
			<th>Datum</th>
			<th>Nemoc</th>
			<th>Část</th>
			<th>Členění</th>
			<th>Ošetření</th>
		</thead>
		<tbody>


			@foreach( $examinations as $examination )

			@foreach( $examination->findings as $finding )

			<form method="POST">

				<tr>

					<th>

						<input class="hidden" id="finding_id" name="finding_id" type="text" value="{{ $finding->id }}">

						<select name="type" id="type">

							<option value=""></option>

							<option <?php echo($finding->type === 'Kontrola') ? 'selected' : '' ?> value="Kontrola">Kontrola</option>

							<option <?php echo($finding->type === 'FUP') ? 'selected' : '' ?> value="FUP">FUP</option>

							<option <?php echo($finding->type === 'Založení') ? 'selected' : '' ?> value="Založení">Založení</option>

							<option <?php echo($finding->type === 'Odebrání obojku') ? 'selected' : '' ?> value="Odebrání obojku">Odebrání obojku</option>

						</select>

					</th>

					<th>

						<input type="text" id="created_at" class="form-control" name="created_at" value="{{ $finding->created_at }}">	

					</th>

					<th>

						@if ( is_object($finding->disease) )

						<select name="disease_id" id="disease_id">
							
							@foreach ( $diseases as $disease )

							<option <?php echo($finding->disease->name == $disease->name) ? 'selected' : '' ?>  value="{{ $disease->id }}">{{ $disease->name }}</option>

							@endforeach

						</select>

						@endif

					</th>

					<th>

						<?php $part = $finding->part()->first() ?>

						@if ( is_object($part) )

						<select name="part_id" id="part_id">

							<option value=""></option>
							
							<option <?php echo($part->name === 'Levá přední') ? 'selected' : '' ?> value="1">Levá přední</option>

							<option <?php echo($part->name === 'Pravá přední') ? 'selected' : '' ?> value="2">Pravá přední</option>

							<option <?php echo($part->name === 'Levá zadní') ? 'selected' : '' ?> value="3">Levá zadní</option>

							<option <?php echo($part->name === 'Pravá zadní') ? 'selected' : '' ?> value="4">Pravá zadní</option>

						</select>	

						@else

						{{ $finding->part_id }}

						@endif

					</th>

					<th>

						<?php $subpart = $finding->subpart()->first() ?>

						@if ( is_object($subpart) )

						<input type="text" id="subpart" class="form-control" name="subpart" value="{{ $subpart->label }}">	

						@else

						{{ $finding->subpart_id }}

						@endif

					</th>

					<th>

						<?php $treatment_translate = $finding->treatment()->first() ?>



						<select name="treatment_id" id="treatment_id">

							<option value=""></option>
							
							@foreach ( $treatments as $treatment )

							@if( is_object($treatment_translate) )

							<option <?php echo($treatment_translate->name == $treatment->name) ? 'selected' : '' ?>  value="{{ $treatment->id }}">{{ $treatment->name }}</option>

							@else

							<option value="{{ $treatment->id }}">{{ $treatment->name }}</option>

							@endif

							@endforeach

						</select>



					</th>
					
					<th>

						<button type="submit" class="btn btn-success" style="width:100%;">
							{{ trans('action.save') }}
						</button>

					</th>

				</tr>

			</form>

			@endforeach

			@endforeach

		</tbody>

	</table>

</div>

</div>

@stop