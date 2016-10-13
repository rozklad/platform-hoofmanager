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

        .info {
            padding-top: 25px;
            padding-bottom: 25px;
        }

    </style>
@stop

@section('scripts')
    @parent
    <script type="text/javascript">
        var $ = jQuery,
                chart = null;
        $(function(){

            /*d3.json('{{ route('sanatorium.hoofmanager.api.stats') }}', function(data) {
                chart = nv.addGraph(function () {
                    var chart = nv.models.lineChart()
                            .useInteractiveGuideline(true)
                            .x(function (d) {
                                return new Date(d.date)
                            })
                            .y(function (d) {
                                return d.value
                            })
                            .color(d3.scale.category10().range())
                            .clipVoronoi(false);

                    chart.xAxis.tickFormat(function (d) {
                        return d3.time.format('%m/%Y')(new Date(d))
                    });

                    chart.yAxis.tickFormat(function (d) {
                        return d
                    });

                    d3.select('#chart-disease svg')
                            .datum(data)
                            .call(chart);

                    nv.utils.windowResize(chart.update);

                    return chart;
                });
            });

            // Top diseases

            d3.json('{{ route('sanatorium.hoofmanager.api.topdiseasesstats') }}', function(data) {
                chart = nv.addGraph(function () {
                    var chart = nv.models.discreteBarChart()
                            .x(function(d) { return d.label })
                            .y(function(d) { return d.value })
                            .staggerLabels(false)
                            .tooltips(false)
                            .showValues(true)

                    d3.select('#chart-top-diseases svg')
                            .datum(data)
                            .call(chart)
                    ;

                    nv.utils.windowResize(chart.update);

                    return chart;
                });
            });

            // Top treatments

            d3.json('{{ route('sanatorium.hoofmanager.api.toptreatmentsstats') }}', function(data) {
                chart = nv.addGraph(function () {
                    var chart = nv.models.discreteBarChart()
                            .x(function(d) { return d.label })
                            .y(function(d) { return d.value })
                            .staggerLabels(false)
                            .tooltips(false)
                            .showValues(true)

                    d3.select('#chart-top-treatments svg')
                            .datum(data)
                            .call(chart)
                    ;

                    nv.utils.windowResize(chart.update);

                    return chart;
                });
            });

            // Worst items

            d3.json('{{ route('sanatorium.hoofmanager.api.worstitemsstats') }}', function(data) {
                chart = nv.addGraph(function () {
                    var chart = nv.models.discreteBarChart()
                            .x(function(d) { return d.label })
                            .y(function(d) { return d.value })
                            .staggerLabels(false)
                            .tooltips(false)
                            .showValues(true)

                    d3.select('#chart-worst-items svg')
                            .datum(data)
                            .call(chart)
                    ;

                    nv.utils.windowResize(chart.update);

                    return chart;
                });
            });

            // Findings in month

            d3.json('{{ route('sanatorium.hoofmanager.api.findingsmonth') }}', function(data) {
                chart = nv.addGraph(function () {
                    var chart = nv.models.pieChart()
                            .x(function(d) { return d.label })
                            .y(function(d) { return d.value })
                            .donutRatio(0.4)
                            .donut(true)
                            .showLabels(true);

                    d3.select('#chart-findings-month svg')
                            .datum(data.data)
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
                            .text(data.count)
                            .attr("class", "middle")
                            .attr("text-anchor", "middle")
                            .attr("dy", ".95em")
                            .style("font-size", "24px")
                            .style("fill", "#000");

                    return chart;
                });
            });*/


        });
    </script>
@stop

{{-- Page content --}}
@section('page')

    <!--<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="basicHeader">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#basic" aria-expanded="true" aria-controls="basic">
                        Basic
                    </a>
                </h4>
            </div>
            <div id="basic" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="basicHeader">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="text-center">Vývoj nemocí</h4>
                            <div id="chart-disease" style="width:100%;height:400px;">
                                <svg></svg>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h4 class="text-center">Nejčastější nemoci</h4>
                            <div id="chart-top-diseases" style="width:100%;height:400px;">
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="text-center">Nejčastější ošetření</h4>
                            <div id="chart-top-treatments" style="width:100%;height:400px;">
                                <svg></svg>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h4 class="text-center">Nejproblémovější zvířata</h4>
                            <div id="chart-worst-items" style="width:100%;height:400px;">
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="text-center">Nálezy za měsíc srpen 2016</h4>
                            <div id="chart-findings-month" style="width:100%;height:400px;">
                                <svg></svg>
                            </div>
                        </div>
                        <div class="col-sm-6">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="advancedHeader">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="advanced">
                        Advanced
                    </a>
                </h4>
            </div>
            <div id="advanced" class="panel-collapse collapse" role="tabpanel" aria-labelledby="advancedHeader">
                <div class="panel-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="expertHeader">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#expert" aria-expanded="false" aria-controls="expert">
                        Expert
                    </a>
                </h4>
            </div>
            <div id="expert" class="panel-collapse collapse" role="tabpanel" aria-labelledby="expertHeader">
                <div class="panel-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                </div>
            </div>
        </div>
    </div>-->



    <div class="row">

        <div class="col-md-12">

            <h2 class="card-header" id="houses">Getting started</h2>

            <p class="lead card-row" style="line-height: 1.5; letter-spacing: 1px;">

                Vyvinuli jsme Hoof Manager na pomoc ošetřovatelům identifikovat, rychle zaznamenat a následně sledovat nemoci kopyt u skotu. Je součástí integrované strategie pro identifikaci, prevenci a záznam nemocí postihující paznehty skotu všech věkových kategorií. Kromě nemocí lze zaznamenávat i aplikovanou léčbu. Všechna nasbíraná data jsou přehledně dostupná ve webovém rozhraní.

            </p>

            <div class="image-wrapper text-center" style="padding-bottom: 15px;">

                <a class="text-center" href="https://s3.amazonaws.com/fortrabbit/app/Hoof-Manager.apk">

                    <img width="15%;" src="{{ Asset::getUrl('sanatorium/hoofmanager::android.png') }}" alt="Download">

                </a>

                <a href="https://s3.amazonaws.com/fortrabbit/app/Manual.doc" class="text-center">Manuál ke stažení</a>

            </div>

            <div class="info">

                <h3 class="card-row">Co Hoof Manager umí?</h3>

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