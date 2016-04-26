@extends('layouts/default')

@section('sidenav')
@parent
@include('sanatorium/hoofmanager::partials/sidenav')
@stop

{{-- Inline styles --}}
@section('styles')
@parent
<style type="text/css">

	.info {
		padding-top: 25px;
		padding-bottom: 25px;
	}

</style>
@stop

{{-- Page content --}}
@section('page')

</div>

</div>

<div class="row">

	<h2 class="card-header" id="houses">Getting started</h2>

	<div class="col-md-12">

		<h4 class="card-row text-center" style="line-height: 1.5; letter-spacing: 1px;">

			Vyvinuli jsme Hoof Manager na pomoc ošetřovatelům identifikovat, rychle zaznamenat a následně sledovat nemoci kopyt u skotu. Je součástí integrované strategie pro identifikaci, prevenci a záznam nemocí postihující kopyta skotu všech věkových kategorií. Kromě nemocí lze zaznamenávat i aplikovanou léčbu. Všechna nasbíraná data jsou přehledně dostupná ve webovém rozhraní.

		</h4>

		<div class="image-wrapper text-center" style="padding-bottom: 15px;">

			<img width="40%" src="{{ Asset::getUrl('standard/img/mockup.png') }}" alt="Mockup" style="display: block; margin-left: auto; margin-right:auto;">

			<a class="text-center" href="{{ Asset::getUrl('standard/app/Hoof-Manager.apk') }}">

				<img width="15%;" src="{{ Asset::getUrl('standard/img/download.png') }}" alt="Download">

			</a>

			<a href="{{ Asset::getUrl('standard/app/Manual.doc') }}" class="text-center">Manuál ke stažení</a>

		</div>

		<div class="info">

			<h3 class="card-row">Co Hoof Manager umí?</h4>

				<h4 class="card-row">Chovy</h4>

				<span class="card-row">Umožní Vám vytvářet si a spravovat chovy, které navštěvujete.</span>

				<h4 class="card-row">Zvířata</h4>

				<span class="card-row">Umožní Vám sledovat zvířata přiřazená do jednotlivých chovů. Můžete si pomocí aplikace ukládat jejich zdravotní stav, informace o kontrolách či funkčních úpravách paznehtů.</span>

				<h4 class="card-row">Plán</h4>

				<span class="card-row">Umožní Vám naplánovat si kontroly v jednotlivých chovech a u jednotlivých zvířat.</span>

				<h4 class="card-row">Synchronizace</h4>

				<span class="card-row">Umožní Vám veškerá nasbíraná data synchronizovat s webovým rozhraním, kde je budete mít přehledně a kdykoliv k dispozici.</span>

			</div>

		</div>

	</div>

	@stop