<?php namespace Sanatorium\Hoofmanager\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Sanatorium\Hoofmanager\Models\Vet;

class HousesController extends Controller {

    /**
     * Return the main view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('sanatorium/hoofmanager::index');
    }

    public function edit($id)
    {
        return $this->showForm($id);
    }

    public function update($id)
    {
        return $this->processForm('update', $id);
    }

    /**
     * Show the form for creating new houses.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return $this->showForm('create');
    }

    /**
     * Handle posting of the form for creating new houses.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        return $this->processForm('create');
    }

    public function showForm(/* $mode = 'create' */ $id = null)
    {
        $houses = app('sanatorium.hoofmanager.houses');

        $items = app('sanatorium.hoofmanager.items');

        $vet = Vet::getVet();

        if ( $id )
        {
            $house = $houses->find($id);
        }
        if ( !is_object($house) )
        {
            $house = $houses->createModel();

            $item = $items->createModel();
        }

        $houseid = $house->id;

        $findingsByHouse = app('sanatorium.hoofmanager.finding')->whereHas('item', function($q) use ($houseid) {

                return $q->whereHas('houses', function($q) use ($houseid) {

                    return $q->where('houses.id', $houseid);

                });

        })->whereNotNull('check_date')->where('check_date', '!=' , '0000-00-00 00:00:00')->orderBy('check_date', 'ASC')->get();

        $checks = [];

        foreach ( $findingsByHouse as $finding ) {

            $item_id = $finding->item_id;

            $finding_item = $items->where('id', $item_id)->first();

            if ( is_object( $finding->disease()->first() ) ) {

                $disease_name = $finding->disease()->first()->name;

            } else {

                $disease_name = 'Bez nálezu';

            }

            if ( is_object( $finding->treatment()->first() ) ) {

                $treatment_name = $finding->treatment()->first()->name;

            } else {

                $treatment_name = 'Bez ošetření';

            }

            $checks [] = [
                'item_id' => $finding_item->id,
                'item_number' => $finding_item->item_number,
                'data' => [
                    'disease'       => $disease_name,
                    'treatment'     => $treatment_name,
                    'check_date'    => substr($finding->check_date, 0, -strpos($finding->check_date, " ") + 1),
                    'type'          => $finding->type,
                ]
            ];

        }

        return view('sanatorium/hoofmanager::houses/form', compact('house', 'vet', 'item', 'checks'));
    }

    /**
     * Processes the form.
     *
     * @param  string  $mode
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function processForm($mode, $id = null)
    {
        $this->houses = app('sanatorium.hoofmanager.houses');

        $this->items = app('sanatorium.hoofmanager.items');

        // Store the houses
        list($messages, $actual_house) = $this->houses->store($id, request()->house);

        foreach (request()->item as $item) {

            if ( isset($item['item_number']) && $item['item_number'] != '' ) {

                list($messages_item, $actual_item) = $this->items->store(null, $item);

                $actual_item->houses()->save($actual_house);

            }

        }

        // Do we have any errors?
        if ($messages->isEmpty())
        {
            $this->alerts->success(trans("sanatorium/hoofmanager::houses/message.success.{$mode}"));

            return redirect()->route('sanatorium.hoofmanager.front');
        }

        $this->alerts->error($messages, 'form');

        return redirect()->back()->withInput();
    }

}
