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

	select {
		height: 100%;
		width: 100%;
	}

	a.info{
		position:relative;
		z-index:24;
		color:#000;
		text-decoration:none
	}

	a.info:hover{
		z-index:25;
	}

	a.info span{
		display: none
	}

	a.info:hover span{
		display:block;
		position:absolute;
		top:2em; left:2em; 
		width:15em;
		border:1px solid #0cf;
		background-color:#cff; 
		color:#000;
		text-align: center
	}

</style>
@stop

{{-- Page content --}}
@section('page')

<div class="row">

	<h2 class="card-header">

		<?php 

		$interval = date_diff(date_create(), date_create($item->birthday));

		//$age = $interval->format("%Y, %M Měsíců, %d Dní");

		if ( $interval->y ) {

			if ( $interval->y <= 1 ) {

				$year = 'rok';

			} else if ( $interval->y <= 4 ) {

				$year = 'roky';

			} else if ( $interval->y >= 5 ){

				$year = 'let';

			}

		} else {

			$year = 'let';

		}

		if ( $interval->m ) {

			if ( $interval->m <= 1 ) {

				$month = 'měsíc';

			} else if ( $interval->m <= 4 ) {

				$month = 'měsíce';

			} else if ( $interval->m >= 5 ) {

				$month = 'měsíců';

			}

		} else {

			$month = 'měsíců';

		}

		if ( $interval->d ) {

			if ( $interval->d <= 1 ) {

				$day = 'den';

			} else if ( $interval->d <= 4 ) {

				$day = 'dny';

			} else if ( $interval->d >= 5 ) {

				$day = 'dní';

			}

		} else {

			$day = 'dní';

		}

		$age  = $interval->format('%y ' . $year . ', %m ' . $month . ', %d ' . $day);

		?>

		Karta zvířete - # {{ $item->item_number }}, Plemeno: nespecifikovano, Věk: {{ $age }}

	</h2>

	<div class="col-md-12">

		<span class="card-row">

			<form method="POST">

				<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

				<div class="form-group">

					<label for="cattle_id" class="control-label">Chov 

						<a class="info" href="#"><i class="icon ion-help-circled"></i>

							<span>Zobrazen chov, do kterého kráva aktuálně patří, s možností přeřadit jí do jiného</span>

						</a>

					</label>

					<select class="form-control" id="cattle_id" name="cattle_id">

						@foreach ( $houses as $cattle )

						<option value="{{ $cattle->id }}"  <?= ($cattle->id == $house->id ?  'selected ' : '') ?>># {{ $cattle->cattle_number }}, {{ $cattle->company_name }}</option>

						@endforeach

					</select>

				</div>

				<div class="form-group">

					<label for="collar" class="control-label">Obojek 

						<a class="info" href="#"><i class="icon ion-help-circled"></i>

							<span>Zobrazen aktuální obojek krávy, pokud nějaký má. Lze přidat, změnit, odebrat</span>

						</a>

					</label>

					<input type="text" id="collar" class="form-control" name="collar" value="{{ $item->collar }}">

				</div>

				<div class="form-group">
					
					<label for="birthday">Narození zvířete 

						<a class="info" href="#"><i class="icon ion-help-circled"></i>

							<span>Datum narození zvířete</span>

						</a>

					</label>

					<input type="date" id="birthday" class="form-control" name="birthday" value="{{ $item->birthday }}">

				</div>

				<div class="form-group">
					
					<label for="breed">Plemeno 

						<a class="info" href="#"><i class="icon ion-help-circled"></i>

							<span>Vyberte plemeno zvířete</span>

						</a>

					</label>

					<select name="breed" id="breed">
						
						<option value="nespecifikovano">Nespecifikovano</option>

					</select>

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