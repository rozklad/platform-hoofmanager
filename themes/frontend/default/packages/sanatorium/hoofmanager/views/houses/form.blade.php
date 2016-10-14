@extends('layouts/default_sidebar')

{{-- Chart lib --}}
{{ Asset::queue('nvd3', 'sanatorium/hoofmanager::nvd3/nv.d3.min.css', 'style') }}
{{ Asset::queue('d3', 'sanatorium/hoofmanager::nvd3/lib/d3.v3.js', 'jquery') }}
{{ Asset::queue('nvd3', 'sanatorium/hoofmanager::nvd3/nv.d3.min.js', 'jquery') }}
{{ Asset::queue('utils', 'sanatorium/hoofmanager::nvd3/src/utils.js', 'jquery') }}
{{ Asset::queue('tooltip', 'sanatorium/hoofmanager::nvd3/src/tooltip.js', 'jquery') }}
{{ Asset::queue('interactiveLayer', 'sanatorium/hoofmanager::nvd3/src/interactiveLayer.js', 'jquery') }}
{{ Asset::queue('axis', 'sanatorium/hoofmanager::nvd3/src/models/axis.js', 'jquery') }}
{{ Asset::queue('line', 'sanatorium/hoofmanager::nvd3/src/models/line.js', 'jquery') }}
{{ Asset::queue('lineWithFocusChart', 'sanatorium/hoofmanager::nvd3/src/models/lineWithFocusChart.js', 'jquery') }}

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

        .buttons-wrapper span {
            margin: 10px 0 20px 0;
        }

    </style>
@stop

{{-- Page content --}}
@section('page')

    <div class="row">

        <form method="POST">

            <h2 class="card-header">
                <a href="{{ route('sanatorium.hoofmanager.front') }}"><i class="ion-ios-arrow-thin-left"></i>	</a>

                @if ( isset($house) )
                    {{ $house->company_name }}
                @else
                    Vytvořit chov
                @endif
            </h2>

            <fieldset class="col-md-12">

                @if ( !isset($house) )

                    <input type="text" id="user_id" name="house[user_id]" value="{{ $vet->id }}" hidden>

                @endif

                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

                <div class="form-group">
                    <label for="company_name" class="control-label">Název chovu</label>
                    <input type="text" id="company_name" class="form-control" name="house[company_name]" value="<?php echo isset($house) ? $house->company_name : ' ' ;?>">
                </div>
                <div class="form-group">
                    <label for="cattle_number" class="control-label">ID chovu</label>
                    <input type="text" id="cattle_number" class="form-control" name="house[cattle_number]" value="<?php echo isset($house) ? $house->cattle_number : ' ' ;?>">
                </div>
                <div class="form-group">
                    <label for="address_line_1" class="control-label">Adresa řádek 1</label>
                    <input type="text" id="address_line_1" class="form-control" name="house[address_line_1]" value="<?php echo isset($house) ? $house->address_line_1 : ' ' ;?>">
                </div>
                <div class="form-group">
                    <label for="address_line_2" class="control-label">Adresa řádek 2</label>
                    <input type="text" id="address_line_2" class="form-control" name="house[address_line_2]" value="<?php echo isset($house) ? $house->address_line_2 : ' ' ?>">
                </div>
            </fieldset>

            <div class="col-md-12">

                <ul class="nav nav-tabs">

                    <li>

                        <a data-toggle="tab" href="#animals">

                            <h3>Zvířata</h3>

                        </a>

                    </li>

                    <li class="active">

                        <a data-toggle="tab" href="#stats">

                            <h3>Statistiky</h3>

                        </a>

                    </li>

                </ul>

                <div class="tab-content">

                    <div id="animals" class="tab-pane fade">

                        <div class="col-md-12">

                            @if ( isset($house) )

                                @if ( count($house->items) > 0 )

                                    @foreach ( $house->items as $item )

                                        <a href="{{ route('sanatorium.hoofmanager.items.edit', ['id' => $item->id]) }}" class="card-row">

                                            Číslo: # {{ $item->item_number }}

                                        </a>

                                        <hr>

                                    @endforeach

                                @else

                                    Nejsou žádné

                                @endif

                            @endif

                            <h3>

                                Nová zvířata

                            </h3>

                            <div class="form-group inline-form new_items" data-item-count="0">

                                <input type="text" id="user_id" name="item[0][user_id]" value="{{ $vet->id }}" hidden>

                                <label for="item_number">Číslo zvířete</label>

                                # <input type="text" id="item_number" name="item[0][item_number]" class="form-control" require>

                            </div>

                        </div>

                        <fieldset class="col-md-12">
                            <div class="form-group buttons-wrapper">

                                <span class="btn btn-primary btn-block" id="more_items">Přidat další</span>

                                <button type="submit" class="btn btn-success" style="width:100%;">

                                    {{ trans('action.save') }}

                                </button>
                            </div>
                        </fieldset>

                    </div> <!-- Animals tab -->

                    <div id="stats" class="tab-pane fade in active">

                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="text-center">Nejčastější nemoci</h4>
                                <div id="chart-top-diseases" style="width:100%;height:400px;">
                                    <div class="ajax-loading" style="width:100%; height: 100%; background: url({{ Asset::getUrl('sanatorium/hoofmanager::ajax-loader.gif') }}) no-repeat center;"></div>
                                    <svg></svg>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h4 class="text-center">Nejčastější ošetření</h4>
                                <div id="chart-top-treatments" style="width:100%;height:400px;">
                                    <div class="ajax-loading" style="width:100%; height: 100%; background: url({{ Asset::getUrl('sanatorium/hoofmanager::ajax-loader.gif') }}) no-repeat center;"></div>
                                    <svg></svg>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-6">

                                <h4 class="text-center">Nejproblémovější zvířata</h4>
                                <div id="chart-worst-items" style="width:100%;height:400px;">
                                    <div class="ajax-loading" style="width:100%; height: 100%; background: url({{ Asset::getUrl('sanatorium/hoofmanager::ajax-loader.gif') }}) no-repeat center;"></div>
                                    <svg></svg>
                                </div>

                            </div>

                            <div class="col-sm-6">

                                <h4 class="text-center">Nálezy za rok 2016</h4>
                                <div id="chart-findings-month" style="width:100%;height:400px;">
                                    <div class="ajax-loading" style="width:100%; height: 100%; background: url({{ Asset::getUrl('sanatorium/hoofmanager::ajax-loader.gif') }}) no-repeat center;"></div>
                                    <svg></svg>
                                </div>

                            </div>

                        </div>

                    </div> <!-- Stats tab -->

                </div> <!-- Tab content -->

            </div> <!-- col -->

        </form>

    </div><!-- row -->

@stop

@section('scripts')

    <script>

        $(function(){

            var itemCount = 1,
                    chart = null;

            $('#more_items').on('click', function(){

                $('.new_items').last().after('<div class="form-group inline-form new_items" data-item-count="' + itemCount + '"><input type="text" id="user_id" name="item[' + itemCount + '][user_id]" value="{{ $vet->id }}" hidden><label for="item_number">Číslo zvířete</label># <input type="text" id="item_number" name="item[' + itemCount + '][item_number]" class="form-control" require></div>');

                itemCount++;

            });

            $(document).on({

                ajaxStart: function() {
                    console.log('start');
                },
                ajaxStop: function() {
                    $('.ajax-loading').remove();
                }

            });

            $.ajax({
                method: "GET",
                url: "{{ route('sanatorium.hoofmanager.api.housestats', ['id' => $house->id]) }}",
            }).done(function( data ) {

                // Charts

                // Top Diseases

                chart = nv.addGraph(function () {
                    var chart = nv.models.discreteBarChart()
                            .x(function(d) { return d.label })
                            .y(function(d) { return d.value })
                            .staggerLabels(true)
                            .tooltips(false)
                            .showValues(true)
                            .noData('Nedostatek dat')

                    d3.select('#chart-top-diseases svg')
                            .datum(data.top_diseases)
                            .call(chart)
                    ;

                    nv.utils.windowResize(chart.update);

                    return chart;
                })

                // Top Treatments

                chart = nv.addGraph(function () {
                    var chart = nv.models.discreteBarChart()
                            .x(function(d) { return d.label })
                            .y(function(d) { return d.value })
                            .staggerLabels(false)
                            .tooltips(false)
                            .showValues(true)
                            .noData('Nedostatek dat')

                    d3.select('#chart-top-treatments svg')
                            .datum(data.top_treatments)
                            .call(chart)
                    ;

                    nv.utils.windowResize(chart.update);

                    return chart;
                })

                // Worst items

                chart = nv.addGraph(function () {
                    var chart = nv.models.discreteBarChart()
                            .x(function(d) { return d.label })
                            .y(function(d) { return d.value })
                            .staggerLabels(false)
                            .tooltips(false)
                            .showValues(true)

                    d3.select('#chart-worst-items svg')
                            .datum(data.worst_items)
                            .call(chart)
                    ;

                    nv.utils.windowResize(chart.update);

                    return chart;
                });

                // Findings in year

                chart = nv.addGraph(function () {
                    var chart = nv.models.pieChart()
                            .x(function(d) { return d.label })
                            .y(function(d) { return d.value })
                            .donutRatio(0.4)
                            .donut(true)
                            .showLabels(true);

                    d3.select('#chart-findings-month svg')
                            .datum(data.findings_year.data)
                            .call(chart)
                    ;

                    nv.utils.windowResize(chart.update);

                    var svg = d3.select("#chart-findings-month svg");

                    var donut = svg.selectAll("g.nv-slice").filter(
                            function (d, i) {
                                return i == 0;
                            }
                    );

                    // Insert first line of text into middle of donut pie chart
                    donut.insert("text", "g")
                            .text("Celkem")
                            .attr("class", "middle")
                            .attr("text-anchor", "middle")
                            .attr("dy", "-.55em")
                            .style("font-size", "24px")
                            .style("fill", "#000");

                    donut.insert("text", "g")
                            .text(data.findings_year.count)
                            .attr("class", "middle")
                            .attr("text-anchor", "middle")
                            .attr("dy", ".95em")
                            .style("font-size", "24px")
                            .style("fill", "#000");

                    return chart;
                });

            }); // End of Ajax call for charts data

        }); // End of ready

    </script>

@stop