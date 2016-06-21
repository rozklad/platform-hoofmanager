<<<<<<< HEAD
<div class="list-group panel sidemenu">
	@if ( Route::has('user.profile') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('user.profile') }}">
			<i class="menu-icon ion-person"></i>
			Můj účet
		</a>
	</div>
	@endif
	@if ( Route::has('sanatorium.hoofmanager.start') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('sanatorium.hoofmanager.start') }}">
			<i class="menu-icon ion-ios-keypad"></i>
			Getting started
		</a>
	</div>
	@endif
	@if ( Route::has('sanatorium.hoofmanager.front') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('sanatorium.hoofmanager.front') }}">
			<i class="menu-icon ion-ios-keypad"></i>
			Chovy
		</a>
	</div>
	@endif
	@if ( Route::has('sanatorium.hoofmanager.animals') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('sanatorium.hoofmanager.animals') }}">
			<i class="menu-icon ion-ios-paw"></i>
			Zvířata
		</a>
	</div>
	@endif
	@if ( Route::has('sanatorium.hoofmanager.plan') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('sanatorium.hoofmanager.plan') }}">
			<i class="menu-icon ion-calendar"></i>
			Plán
		</a>
	</div>
	@endif
	@if ( Route::has('sanatorium.hoofmanager.stats') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('sanatorium.hoofmanager.stats') }}">
			<i class="menu-icon ion-pie-graph"></i>
			Statistiky
		</a>
	</div>
	@endif
=======
<div class="list-group panel sidemenu">
	@if ( Route::has('user.profile') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('user.profile') }}">
			<i class="menu-icon ion-person"></i>
			Můj účet
		</a>
	</div>
	@endif
	@if ( Route::has('sanatorium.hoofmanager.start') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('sanatorium.hoofmanager.start') }}">
			<i class="menu-icon ion-ios-keypad"></i>
			Getting started
		</a>
	</div>
	@endif
	@if ( Route::has('sanatorium.hoofmanager.front') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('sanatorium.hoofmanager.front') }}">
			<i class="menu-icon ion-ios-keypad"></i>
			Chovy
		</a>
	</div>
	@endif
	@if ( Route::has('sanatorium.hoofmanager.animals') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('sanatorium.hoofmanager.animals') }}">
			<i class="menu-icon ion-ios-paw"></i>
			Zvířata
		</a>
	</div>
	@endif
	@if ( Route::has('sanatorium.hoofmanager.plan') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('sanatorium.hoofmanager.plan') }}">
			<i class="menu-icon ion-calendar"></i>
			Plán
		</a>
	</div>
	@endif
	@if ( Route::has('sanatorium.hoofmanager.stats') )
	<div class="list-group-group">
		<a class="list-group-item list-group-item-top" href="{{ route('sanatorium.hoofmanager.stats') }}">
			<i class="menu-icon ion-pie-graph"></i>
			Statistiky
		</a>
	</div>
	@endif
>>>>>>> 434c7e6f18a18ec990cc7933cb97003437355935
</div>