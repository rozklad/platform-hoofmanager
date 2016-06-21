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

	#houses-search {
		padding: 10px;
		border: none;
		border-bottom: 1px solid;
	}

</style>
@stop

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

var housesPhp = <?= $houses ?>;

var housesSource = [];

for ( var i = 0; i < housesPhp.length; i++ ) {
	housesSource.push( housesPhp[i].company_name + ' #' + housesPhp[i].cattle_number);
}

$('#houses-search').typeahead({
	hint: true,
	highlight: true,
	minLength: 1
},
{
	name: 'houses',
	source: substringMatcher(housesSource)
});

$('#houses-search').bind('typeahead:select', function(ev, suggestion) {

	var name = suggestion.substr(0, suggestion.indexOf(' #')); 

	for ( var i = 0; i < housesPhp.length; i++ ) {

		if ( housesPhp[i].company_name == name ) {

			var url = "{{ route('sanatorium.hoofmanager.houses.edit') }}";

			url = url.substr(0, url.indexOf('%'));

			window.location.href = url + housesPhp[i].id;

		}

	}

});

</script>
@stop

{{-- Page content --}}
@section('page')

<div class="row">

	<h2 class="card-header" id="houses">Chovy</h2>

	<p>

		<button class="btn btn-default">

			<a href="{{ route('sanatorium.hoofmanager.houses.create') }}">Nový chov</a>

		</button>

	</p>

	@if ( empty($houses[0]) )

	<div class="container-fluid">

		<div class="jumbotron">

			<h5>

				Nemáte žádné chovy. Pokud chcete vytvořit krávu, musíte nejdříve vytvořit chov.

			</h5>

		</div>

	</div>

	@else

	<input id="houses-search" type="text" placeholder="Vyhledat chov" style="width: 100%;">

	@endif

	@foreach( $houses as $house )

	<a href="{{ route('sanatorium.hoofmanager.houses.edit', ['id' => $house->id]) }}" class="card">

		<h3 class="card-row">
			@if ( $house->company_name )
			{{ $house->company_name }}
			@else
			Nepojmenovaný chov
			@endif

			<!--<a href="{{ route('sanatorium.hoofmanager.houses.edit', ['id' => $house->id]) }}">
				<i class="fa fa-edit"></i>
			</a>-->
		</h3>

		<span class="card-row">
			@if ( $house->cattle_number )
			<span class="text-muted"># {{ $house->cattle_number }}</span>
			@else
			<span class="text-muted">Chybí číslo chovu</span>
			@endif
		</span>

		<span class="card-row">
			@if ( $house->address_line_1 )
			{{ $house->address_line_1 }}<br>
			@endif
		</span>

		<span class="card-row">
			@if ( $house->address_line_2 )
			{{ $house->address_line_2 }}
			@endif
		</span>

	</a>

	<hr>

	@endforeach

	<div class="hidden">
		<h2 id="findings">Nálezy</h2>
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
				@foreach( $examinations as $examination )
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
						@if ( is_object($examination->item) )
						<?php $house = $examination->item->houses()->first(); ?>
						@if ( is_object($house) )
						{{ $house->label }}
						<a href="{{ route('admin.sanatorium.hoofmanager.houses.edit', $house->id) }}">
							<i class="fa fa-edit"></i>
						</a>
						@endif
						@endif
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
				@endforeach
			</tbody>
		</table>

	</div>

</div>
@stop