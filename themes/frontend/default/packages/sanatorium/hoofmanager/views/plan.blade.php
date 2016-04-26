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

<a href="{{ route('sanatorium.hoofmanager.plan.pdf') }}">Tisk</a>

	<h2 class="card-header">

		Plán

	</h2>

	<div class="col-md-12">

		<h3 class="card-row">Naplánované kontroly</h3>

	</div>

	<table class="table">

		<thead>
			<th>Datum kontroly</th>
			<th>Chov</th>
			<th>Zvíře</th>
		</thead>

		<tbody>

			@foreach ( $plans as $plan )

			@foreach ( $plan->findings as $finding )

			<tr>

				@if ( $finding->check_date != '0000-00-00 00:00:00' )

				<th>

					<?php 

					$date_string = $finding->check_date;

					$date_string = substr($date_string, 0, strpos($date_string, " "));

					$date = date_create_from_format('Y-m-d', $date_string);

					echo date("d. m. Y", $date->getTimestamp());

					?>



				</th>

				@endif

				@if ( $finding->check_date != '0000-00-00 00:00:00')

				<?php $house = $plan->item->houses()->first(); ?>

				<th>

					<a href="{{ route('sanatorium.hoofmanager.houses.edit', ['id' => $house->id]) }}">

						# {{ $house->cattle_number }}, <?php echo($house->company_name) ? $house->company_name : 'Název nebyl vyplněn' ?>

					</a>

				</th>

				@endif

				@if ( $finding->check_date != '0000-00-00 00:00:00' )

				<th>

					<a href="{{ route('sanatorium.hoofmanager.items.edit', ['id' => $plan->item_id]) }}">

						{{ $plan->item->item_number }}

					</a>

				</th>

				@endif

			</tr>

			@endforeach

			@endforeach

		</tbody>

	</table>

</div>

@stop