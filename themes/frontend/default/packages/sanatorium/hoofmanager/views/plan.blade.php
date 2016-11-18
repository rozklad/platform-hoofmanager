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

    </style>
@stop

@section('scripts')

    <script>

        $(function(){

            $('#print_house').change(function(){

                var url = "{{ route('sanatorium.hoofmanager.plan.pdf.single')}}";

                url = url.substr(0, url.indexOf('%'));

                window.location.href = url + $(this).val();

            });

        });

    </script>

@stop

{{-- Page content --}}
@section('page')

    <div class="row">

        <a href="{{ route('sanatorium.hoofmanager.plan.pdf.all') }}">Tisk všeho</a>

        <select name="print_house" id="print_house">

            <option value="">Vyberte chov pro tisk plánu</option>

            @foreach ( $houses as $house )

                @if ( is_object($house) )

                    @if ( $house->cattle_number && $house->company_name )

                        <option value="{{ $house->id }}">{{ $house->cattle_number }} {{ $house->company_name }}</option>

                    @endif

                @endif

            @endforeach

        </select>

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

            @foreach ( $plans as $finding )

                <tr>

                    @if ( is_object($finding) )

                        @if ( $finding->check_date != '0000-00-00 00:00:00' )

                            <th>

                                <?php

                                $date_string = $finding->check_date;

                                $date_string = substr($date_string, 0, strpos($date_string, " "));

                                $date = date_create_from_format('Y-m-d', $date_string);

                                echo date("d. m. Y", $date->getTimestamp());

                                ?>



                            </th>

                            @if ( is_object($finding->item) )

                                <?php $house = $finding->item->houses()->first(); ?>

                                @if ( is_object($house) )

                                    <th>

                                        <a href="{{ route('sanatorium.hoofmanager.houses.edit', ['id' => $house->id]) }}">

                                            # {{ $house->cattle_number }}, <?php echo($house->company_name) ? $house->company_name : 'Název nebyl vyplněn' ?>

                                        </a>

                                    </th>

                                @endif
                            @endif


                            @if ( is_object($finding->item) )
                                <th>

                                    <a href="{{ route('sanatorium.hoofmanager.items.edit', ['id' => $finding->item_id]) }}">

                                        {{ $finding->item->item_number }}

                                    </a>

                                </th>
                            @endif

                        @endif

                    @endif

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

@stop