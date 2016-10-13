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

	.new-finding-row th {
		border-bottom: 1px solid;
	}

	.hoof-modal {
		position: fixed;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		z-index: 9999;
		display: none;
		background-color: rgba(0,0,0,0.7);
	}

	.hoof-modal .hoof-modal-body {
		position: absolute;
		top: 30%;
		left: 30%;
		right: 30%;
		background-color: #fff;
	}

	.path-active {
		fill: green;
	}

	#close-hoof-modal {
		position: absolute;
		top: 0;
		right: 0;
		font-size: 40px;
		padding: 5px 15px;
		cursor: pointer;
	}

</style>
@stop

{{-- Scripts --}}
@section('scripts')
@parent

<script type="text/javascript">
	
	$(function(){

		$("#subpart_button").on('click', function(){

			$(".hoof-modal").fadeIn('slow');

		});

		$("#close-hoof-modal").on('click', function(){

			$(".hoof-modal").fadeOut('slow');

		})

		$('.hoof-svg').find("path, rect").on('click', function(){

			var subpart_id = $(this).attr('subpart');

			$(".path-active").attr("class", "");

			$(this).attr("class", "path-active");

			$('#newfinding_subpart_id').val(subpart_id);
		});

	});

</script>

@stop

{{-- Page content --}}
@section('page')

<div class="row">

	<h2 class="card-header">

		<a href="{{ route('sanatorium.hoofmanager.houses.edit', $house->id) }}"><i class="ion-ios-arrow-thin-left"></i>	</a>

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

			@endif
					-->

		</form>

	</span>

	<ul class="nav nav-tabs">

		<li class="active">

			<a data-toggle="tab" href="#nalezy">

				<h3>Nálezy</h3>

			</a>

		</li>

		<li><a data-toggle="tab" href="#kontroly"><h3>Kontroly</h3></a></li>
	</ul>

	<div class="tab-content">

		<div id="nalezy" class="tab-pane fade in active">

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

					<!-- Create new examination and finding -->

					<form method="POST" action="{{ $item->id }}/newfinding">

						<tr class="new-finding-row">

							<th>

								<select class="form-control" name="newfinding[0][type]" id="type">

									<option value="">Zvolte typ</option>

									<option value="Kontrola">Kontrola</option>

									<option value="FUP">FUP</option>

								</select>

							</th>

							<th>

								<input type="datetime" id="created_at" class="form-control" name="newfinding[0][created_at]" value="{{ date('Y-m-d H:i:s') }}">

							</th>

							<th>

								<select class="form-control" name="newfinding[0][disease_id]" id="disease_id">

									<option value="">Vyberte nemoc</option>

									@foreach ( $diseases as $disease )

									<option value="{{ $disease->id }}">{{ $disease->name }}</option>

									@endforeach

								</select>

							</th>

							<th>

								<select class="form-control" name="newfinding[0][part_id]" id="part_id">

									<option value="">Vyberte končetinu</option>

									<option value="1">Levá přední</option>

									<option value="2">Pravá přední</option>

									<option value="3">Levá zadní</option>

									<option value="4">Pravá zadní</option>

								</select>	

							</th>

							<th>

								<!-- TODO část končetiny -->

								<span class="btn btn-succes" style="width:100%" id="subpart_button">

									Vybrat část

								</span>

								<div class="hoof-modal">

									<div class="hoof-modal-body">

										<span id="close-hoof-modal">X</span>

										@include('sanatorium/hoofmanager::items/hoof')

									</div>

								</div>

								<input type="hidden" name="newfinding[0][subpart_id]" id="newfinding_subpart_id">

							</th>

							<th>

								<select class="form-control" name="newfinding[0][treatment_id]" id="treatment_id">

									<option value="">Vyberte ošetření</option>

									@foreach ( $treatments as $treatment )

									<option value="{{ $treatment->id }}">{{ $treatment->name }}</option>

									@endforeach

								</select>

							</th>

							<th>

								<button type="submit" class="btn btn-success" style="width:100%;">
									<!--{{ trans('action.save') }}--> Nový
								</button>

							</th>

						</tr>

					</form>


					@foreach( $examinations as $examination )

					@foreach( $examination->findings as $finding )

					<form method="POST">

						<tr>

							<th>

								<input class="hidden" id="finding_id" name="finding_id" type="text" value="{{ $finding->id }}">

								<select class="form-control" name="type" id="type">

									<option value=""></option>

									<option <?php echo($finding->type === 'Kontrola') ? 'selected' : '' ?> value="Kontrola">Kontrola</option>

									<option <?php echo($finding->type === 'FUP') ? 'selected' : '' ?> value="FUP">FUP</option>

									<option <?php echo($finding->type === 'Založení') ? 'selected' : '' ?> value="Založení">Založení</option>

									<option <?php echo($finding->type === 'Odebrání obojku') ? 'selected' : '' ?> value="Odebrání obojku">Odebrání obojku</option>

								</select>

							</th>

							<th>

								<input type="datetime" id="created_at" class="form-control" name="created_at" value="{{ $finding->created_at }}">	

							</th>

							<th>

								@if ( is_object($finding->disease) )

								<select class="form-control" name="disease_id" id="disease_id">

									@foreach ( $diseases as $disease )

									<option <?php echo($finding->disease->name == $disease->name) ? 'selected' : '' ?>  value="{{ $disease->id }}">{{ $disease->name }}</option>

									@endforeach

								</select>

								@endif

							</th>

							<th>

								<?php $part = $finding->part()->first() ?>

								@if ( is_object($part) )

								<select class="form-control" name="part_id" id="part_id">

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



								<select class="form-control" name="treatment_id" id="treatment_id">

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

		<div id="kontroly" class="tab-pane fade">

			<table class="table">
				<thead>
					<th>Datum kontroly</th>
					<th>Nález</th>
					<th>Část</th>
					<th>Členění</th>
					<th>Ošetření</th>
					<th>Ze kdy</th>
				</thead>
				<tbody>

					@foreach( $examinations as $examination )

					@foreach( $examination->findings as $finding )

					@if ( $finding->check_date && $finding->check_date != "0000-00-00 00:00:00" )

					<tr>

						<th>
							
							{{ date("d. m. Y", strtotime($finding->check_date)) }}

						</th>

						<th>
							
							{{ $finding->disease->name }}

						</th>

						<th>
							
							<?php $part = $finding->part()->first() ?>

							@if ( is_object($part) )

							{{ $part->label }}

							@else

							{{ $finding->part_id }}

							@endif

						</th>

						<th>
							
							<?php $subpart = $finding->subpart()->first() ?>

							@if ( is_object($subpart) )

							{{ $subpart->label }}

							@else

							{{ $finding->subpart_id }}

							@endif

						</th>

						<th>
							
							<?php $treatment_translate = $finding->treatment()->first() ?>

							@if( is_object($treatment_translate) )

							{{ $treatment_translate->name }}

							@else

							{{ $finding->treatment }}

							@endif

						</select>

					</th>

					<th>

						{{ date("d. m. Y", strtotime($finding->created_at)) }}

					</th>

				</tr>

				@endif

				@endforeach

				@endforeach

			</tbody>

		</table>

	</div>

</div>

</div>

</div>
@stop