@extends('layouts/default_sidebar')

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

        .new td {
            border-bottom: 1px solid;
        }

    </style>
@stop

{{-- Page content --}}
@section('page')

    <div class="row">

        <div class="col-md-12">

            <h2 class="card-header" id="houses">Nemoci a ošetření</h2>

        </div>

    </div>

    <div class="row">

        <div class="col-sm-12 col-md-4">

            <h4>Nemoci</h4>

            <table class="table">

                <thead>

                <tr>

                    <th>Název</th>

                    <th></th>

                </tr>

                </thead>

                <tbody>

                <tr class="new">

                    <form method="POST" action="diseasesandtreatments/newdisease/">

                        <td>

                            <input type="text" name="name" placeholder="Název" required>

                        </td>

                        <td>

                            <button type="submit" class="btn btn-success">

                                Nová

                            </button>

                        </td>

                    </form>

                </tr>

                @foreach ( $diseases as $disease )

                    <form action="diseasesandtreatments/disease/{{ $disease->id }}" method="POST">

                        <tr>

                            <td>

                                <input type="text" name="name" value="{{ $disease->name }}" required>

                            </td>

                            <td>

                                <button class="btn btn-success">

                                    {{ trans('action.save')  }}

                                </button>

                            </td>

                        </tr>

                    </form>

                @endforeach

                </tbody>

            </table>

        </div>

        <div class="col-sm-12 col-md-8">

            <h4>Ošetření</h4>

            <table class="table">

                <thead>

                <tr>

                    <th>Název</th>

                    <th>Nemoc</th>

                    <th>FaSy výrobek?</th>

                </tr>

                </thead>

                <tbody>

                <tr class="new">

                    <form action="diseasesandtreatments/newtreatment" method="POST">

                        <td>

                            <input type="text" name="name" placeholder="Název ošetření" required>

                        </td>

                        <td>

                            <select name="disease_id" id="">

                                @foreach ( $diseases as $disease )

                                    <option value="{{ $disease->id }}">{{ $disease->name }}</option>

                                @endforeach

                            </select>

                        </td>

                        <td>

                            <input type="checkbox" name="fasy_vyrobek">

                        </td>

                        <td>

                            <button class="btn btn-success" type="submit">

                                Nové

                            </button>

                        </td>

                    </form>

                </tr>

                @foreach ( $treatments as $treatment )

                    <form action="diseasesandtreatments/treatment/{{ $treatment->id }}" method="POST">

                        <tr>

                            <td>

                                <input name="name" type="text" value="{{ $treatment->name }}" required>

                            </td>

                            <td>

                                <select name="disease_id" id="">

                                    @foreach ( $diseases as $disease )

                                        <option {{ ($disease->id == $treatment->id) ? 'selected' : '' }} value="{{ $disease->id }}">{{ $disease->name }}</option>

                                    @endforeach

                                </select>

                            </td>

                            <td>

                                <input name="fasy_vyrobek" {{ ($treatment->fasy_vyrobek) ?  "checked" : ""}} type="checkbox">

                            </td>

                            <td>

                                <button class="btn btn-success" type="submit">

                                    {{ trans('action.save') }}

                                </button>

                            </td>

                        </tr>

                    </form>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

@stop