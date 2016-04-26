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

	<h2 class="card-header">

		Zvířata

	</h2>

	<table class="table">

		<thead>

			<tr>

				<th style="width: 15%;">Klec/Zvíře</th>

				<th>Chov</th>

				<th>Nemoci</th>

				<th style="width: 10%;">Paznehtář</th>

				<th>Kontrola</th>

				<th>Prevence</th>

				<th>Terapie</th>

			</tr>

		</thead>

		<tbody>

			@foreach ( $houses as $house )

			@foreach ( $house->items as $item)

			<tr>

				<td>

					<a href="{{ route('sanatorium.hoofmanager.items.edit', ['id' => $item->id]) }}" class="card">

						# {{ $item->item_number }}

					</a>

				</td>

				<td>

					<a href="{{ route('sanatorium.hoofmanager.houses.edit', ['id' => $house->id]) }}">

						{{ $house->company_name }} # {{ $house->cattle_number }}

					</a>

				</td>

				<td>
					
					@foreach ( $item->examinations as $examination )

					@foreach ( $examination->findings as $finding )

					@if ( is_object($finding->disease) )

					{{ $finding->disease->name }}, 

					@endif

					@endforeach

					@endforeach

				</td>

				<td>
					
					{{ $vet->first_name }} {{ $vet->last_name }}

				</td>

				<td>
					
					@foreach ( $item->examinations as $examination )

					@foreach ( $examination->findings as $finding )

					@if ( $finding->check_date != '0000-00-00 00:00:00' && isset($finding->check_date) )

					<?php 

					$date_string = $finding->check_date;

					$date_string = substr($date_string, 0, strpos($date_string, " "));

					$date = date_create_from_format('Y-m-d', $date_string);

					echo date("d. m. Y", $date->getTimestamp());

					?>

					@endif

					@endforeach

					@endforeach

				</td>

				<td>

				<?php $diseases_array = []; ?>

					@foreach ( $item->examinations as $examination )

					@foreach ( $examination->findings as $finding )

					@if ( is_object($finding->disease) )

					<?php array_push($diseases_array, $finding->disease->name); ?>

					@endif

					@endforeach

					@endforeach

					{{ end($diseases_array) }}

				</td>

				<td>

				<?php $treatment_array = []; ?>

					@foreach ( $item->examinations as $examination )

					@foreach ( $examination->findings as $finding )

					@if ( is_object($finding->treatment) )

					<?php array_push($treatment_array, $finding->treatment->name); ?>

					@endif

					@endforeach

					@endforeach

					{{ end($treatment_array) }}

				</td>

			</tr>

			@endforeach

			@endforeach

		</tbody>

	</table>

</div>

@stop