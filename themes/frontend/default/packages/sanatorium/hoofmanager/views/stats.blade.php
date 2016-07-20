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

	.statistics {
		width: 65%;
		margin-left: auto;
		margin-right: auto;
		margin-top: 20px;
		margin-bottom: 40px;
	}

	.statistics .statistic-col {
		padding-top: 20px;
		padding-bottom: 20px;
	}

	.statistics .statistic-col:nth-child(2) {

	}

	.ct-label {
		fill: #000;
		font-size: 1.35rem;
	}

	.ct-chart-pie {
		text-align: center;
	}

</style>
@stop

{{-- Page content --}}
@section('page')

<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">

<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

<script   src="https://code.jquery.com/jquery-2.2.1.js"   integrity="sha256-eNcUzO3jsv0XlJLveFEkbB8bA7/CroNpNVk3XpmnwHc="   crossorigin="anonymous"></script>

<div class="row">

	<h2 class="card-header">

		Statistiky

	</h2>

	<div class="row statistics">
		
		<div class="col-md-4 statistic-col">
			
			<h1>{{ count($houses) }}</h1>

			<h4>Spravovaných chovů</h4>

		</div>

		<div class="col-md-4 statistic-col">
			
			<h1>{{ count($items) }}</h1>

			<h4>Ošetřovaných zvířat</h4>

		</div>

		<div class="col-md-4 statistic-col">
			
			<h1>{{ count($findings) }}</h1>

			<h4>Záznamů nálezů</h4>

		</div>

	</div>

	<h3 class="text-center">Podíl výskytu jednotlivých nemocí u všech sledovaných zvířat</h3>

	<div id="pie" class="ct-chart ct-chart-pie"></div>

	<h3 class="text-center">Podíl výskytu jednotlivých nemocí v daném chovu</h3>

	<div class="form-group">
		
		<select class="form-control" name="cattle_stats" id="cattle_stats">

		<option value="0">Vyberte chov</option>
			
			@foreach ( $houses as $house )

			<option value="{{ $house->id }}"># {{ $house->cattle_number }}, {{ $house->company_name }}</option>

			@endforeach

		</select>

	</div>

	<div id="cattle_pie" class="ct-chart ct-chart-pie"></div>

	<!--<h3 class="text-center">Vývoj nemoci u zvířete</h3>

	<div class="col-md-12">

		<form method="GET" id="itemForm">

			<?php if ( isset($_GET['itemID']) ) {

				$acutalID = $_GET['itemID'];

			} else {

				$acutalID = '';

			} ?>

			<select name="itemID" id="itemsSelect">

				<option value="">Vyberte číslo zvířete</option>

				@foreach ( $items as $item )

				<option <?php echo ( $acutalID == $item->id ) ? 'selected' : ''; ?> value="{{ $item->id }}">

					# {{ $item->item_number }}

				</option>

				@endforeach

			</select>

		</form>

		@if ( isset($_GET['itemID']) )

		<?php $diseases_array = []; ?>

		@foreach ( $examinations->where('item_id', $_GET['itemID']) as $examination )

		@if ( count($examination->findings) > 0 )

		<?php  array_push($diseases_array, $examination->findings->lists('disease_id')->first()); ?>

		@endif

		@endforeach

		<?php $test  = array_count_values($diseases_array); 

			//var_dump($test);
		?>

		@endif

		<div id="line" class="ct-chart ct-golden-section"></div>-->

	</div>

</div>

<script>

	<?php

	$to_js_names = json_encode($names);

	$to_js_counts = json_encode($counts);

	echo "var names = ". $to_js_names . ";\n";

	echo "var counts = ". $to_js_counts . ";\n";

	?>

	new Chartist.Pie('#pie', {
		series: counts,
		labels: names
	}, {
		chartPadding: 30,
		labelOffset: 90,
		labelDirection: 'explode',
		width: 700,
		height: 600
	});

	$(function(){

		$.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
        var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

        if (token) {
            return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
        }
    });

		$.ajaxSetup({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
		});



		$("#itemsSelect").change(function(){
			$("#itemForm").submit();
		});

		$('#cattle_stats').on('change', function(){

			$.ajax({
				method: "POST",
				data: { id: $(this).val() }
			})
			.done(function( data ) {
				
				var names = data[0]['names'];

				var counts = data[0]['counts'];

				new Chartist.Pie('#cattle_pie', {
					series: counts,
					labels: names
				}, {
					chartPadding: 30,
					labelOffset: 90,
					labelDirection: 'explode',
					width: 700,
					height: 600
				});

			});

		})

	});

	/*new Chartist.Line('#line', {
		labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
		series: [
		[5, 4, 3, 2, 1]
		]
	}, {
		fullWidth: false,
		width: 800,
		height: 500,
		chartPadding: {
			top: 20
			//right: 40
		}
	});*/
</script>

@stop