<<<<<<< HEAD
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

	.twitter-typeahead {
		width: 100%;
	}

	#items-search {
		border: none;
		border-bottom: 1px solid;
		padding: 10px;
	}

</style>
@stop

{{-- Scripts --}}
@section('scripts')
@parent
<script type="text/javascript">
	var substringMatcher = function(strs) {
		return function findMatches(q, cb) {
			var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
    	if (substrRegex.test(str)) {
    		matches.push(str);
    	}
    });

    cb(matches);
};
};

var houses = <?= $houses ?>;

var items = <?= json_encode($items) ?>;

var itemsPhp = [];

for ( var i = 0; i < houses.length; i++) {

	for ( var x = 0; x < houses[i].items.length; x++ ) {

		if ( houses[i].company_name ) {

			itemsPhp.push("# " + houses[i].items[x].item_number + " - " + houses[i].company_name);

		} else {

			itemsPhp.push("# " + houses[i].items[x].item_number);

		}

	}

}

$(function(){

	$('#items-search').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	},
	{
		name: 'items',
		source: substringMatcher(itemsPhp)
	});

	$('#items-search').bind('typeahead:select', function(ev, suggestion) {

		var name = suggestion.substr(0, suggestion.indexOf(' -')).replace('# ', '');

		var url = "{{ route('sanatorium.hoofmanager.items.edit') }}";

		url = url.substr(0, url.indexOf('%'));

		for ( var klic in items ) {

			var item = items[klic];

			if ( item.item_number == name ) {

				var id = item.id;

				window.location.href = url + id;

			}

		}

	});

});



</script>
@stop

{{-- Page content --}}
@section('page')

<div class="row">

	<h2 class="card-header">

		Zvířata

	</h2>

	<input id="items-search" type="text" placeholder="Vyhledat zvíře" style="width: 100%;">

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

=======
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

	.twitter-typeahead {
		width: 100%;
	}

	#items-search {
		border: none;
		border-bottom: 1px solid;
		padding: 10px;
	}

</style>
@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">
	var substringMatcher = function(strs) {
		return function findMatches(q, cb) {
			var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
    	if (substrRegex.test(str)) {
    		matches.push(str);
    	}
    });

    cb(matches);
};
};

var houses = <?= $houses ?>;

var itemsPhp = [];

for ( var i = 0; i < houses.length; i++) {

	for ( var x = 0; x < houses[i].items.length; x++ ) {

		if ( houses[i].company_name ) {

			itemsPhp.push("# " + houses[i].items[x].item_number + " - " + houses[i].company_name);

		} else {

			itemsPhp.push("# " + houses[i].items[x].item_number);

		}

	}

}

//console.log(itemsPhp);

$('#items-search').typeahead({
	hint: true,
	highlight: true,
	minLength: 1
},
{
	name: 'items',
	source: substringMatcher(itemsPhp)
});

$('#items-search').bind('typeahead:select', function(ev, suggestion) {

	var name = suggestion.substr(0, suggestion.indexOf(' #')); 

	for ( var i = 0; i < itemsPhp.length; i++ ) {

		if ( itemsPhp[i].company_name == name ) {

			var url = "{{ route('sanatorium.hoofmanager.items.edit') }}";

			url = url.substr(0, url.indexOf('%'));

			window.location.href = url + itemsPhp[i].id;

		}

	}

});

</script>
@stop

{{-- Page content --}}
@section('page')

<div class="row">

	<h2 class="card-header">

		Zvířata

	</h2>

	<input id="items-search" type="text" placeholder="Vyhledat zvíře" style="width: 100%;">

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

>>>>>>> 434c7e6f18a18ec990cc7933cb97003437355935
@stop