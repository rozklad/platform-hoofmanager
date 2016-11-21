<?php namespace Sanatorium\Hoofmanager\Controllers\Api;

use Input;
use Sanatorium\Hoofmanager\Models\Vet;
use Sanatorium\Hoofmanager\Models\House;
use Sanatorium\Hoofmanager\Models\Finding;
use Sanatorium\Hoofmanager\Models\Item;
use Sentinel;

class VetController extends ApiController {


    /**
     * Show the form for creating new houses.
     *
     * @return \Illuminate\View\View
     */
    public function auth()
    {
        $credentials = [
            'email'    => Input::get('email'),
            'password' => Input::get('password'),
        ];

        $diseases = app('sanatorium.hoofmanager.diseases')->get();

        $treatments = app('sanatorium.hoofmanager.treatment')->get();

        $items = app('sanatorium.hoofmanager.items')->get();

        $item_numbers = [];

        foreach( $items as $item ) {
            if ( $item->item_number )
                array_push($item_numbers, $item->item_number);
        }

        $houses = app('sanatorium.hoofmanager.houses')->get();

        $cattle_numbers = [];

        foreach ( $houses as $house ) {
            if ( $house->cattle_number )
                array_push($cattle_numbers, $house->cattle_number);
        }


        if ($user = Sentinel::authenticate($credentials))
        {
            $vet = Vet::getVet();
            $this->status = 200;
            $this->result = $vet;
            $this->result->diseases = $diseases;
            $this->result->treatments = $treatments;
            $this->result->items_numbers = $item_numbers;
            $this->result->cattles_numbers = $cattle_numbers;

            if ( $vet->isAdmin() ) {
                $houses = app('sanatorium.hoofmanager.houses')->get();
                $findingsAll = app('sanatorium.hoofmanager.finding')->get();
            } else {
                $findingsAll = app('sanatorium.hoofmanager.finding')->where('user_id', $vet->id)->get();
                $houses = app('sanatorium.hoofmanager.houses')->where('user_id', $vet->id)->get();
            }

            $this->result->houses = $houses;

            $findings = [];

            foreach ( $findingsAll as $finding ) {

                $item_number = app('sanatorium.hoofmanager.items')->find($finding->item_id)->item_number;

                $findings[$item_number][] = $finding;

            }

            $this->result->findings = $findings;

        }
        else
        {
            $this->status = 403;
            $this->result = ['success' => false];
        }
        return $this->result;
    }

    public function find($id = null)
    {
        // @todo make more efficient
        $vet = Vet::find($id);

        $items = app('sanatorium.hoofmanager.items')->get();

        $item_numbers = [];

        foreach( $items as $item ) {
            if ( $item->item_number )
            array_push($item_numbers, $item->item_number);
        }

        $houses = app('sanatorium.hoofmanager.houses')->get();

        $cattle_numbers = [];

        foreach ( $houses as $house ) {
            if ( $house->cattle_number )
            array_push($cattle_numbers, $house->cattle_number);
        }

        if ( $vet->isAdmin() ) {

            $houses = app('sanatorium.hoofmanager.houses')->get();
            $findingsAll = app('sanatorium.hoofmanager.finding')->get();

        } else {

            $findingsAll = app('sanatorium.hoofmanager.finding')->where('user_id', $vet->id)->get();
            $houses = app('sanatorium.hoofmanager.houses')->where('user_id', $vet->id)->get();

        }

        $findings = [];

        foreach ( $findingsAll as $finding ) {

            $item_number = app('sanatorium.hoofmanager.items')->find($finding->item_id)->item_number;

            $findings[$item_number][] = $finding;

        }

        $diseases = app('sanatorium.hoofmanager.diseases')->get();

        $treatments = app('sanatorium.hoofmanager.treatment')->get();

        $vet->houses = $houses;
        $vet->findings = $findings;
        $vet->diseases = $diseases;
        $vet->treatments = $treatments;
        $vet->items_numbers = $item_numbers;
        $vet->cattles_numbers = $cattle_numbers;

        return $vet;
    }

    public function checks($id = null)
    {
        $vet = Vet::find($id);

        $checks = [];

        $items = [];

        $findings = Finding::whereHas('examination', function($q)  use ($vet) {

            return $q->where('user_id', $vet->id);

        })->whereNotNull('check_date')->where('check_date', '!=', '0000-00-00 00:00:00')->get();

        foreach ($findings as $finding) {

            $item_id = $finding->examination()->first()->item_id;

            if ( Item::find($item_id) ) {

                $item = Item::find($item_id);

                $house = $item->houses()->first();

                $finding['cattle_number'] = $house->cattle_number;

                $finding['item_number'] = $item->item_number;

                /*$examination[$finding->id] = [$finding];

                $items[$item_id] = ['item_id' => $item_id, 'item_number' => $item->item_number, 'findings' => $examination];

                $checks[$house->id] = ['house_id' => $house->id, 'cattle_number' => $house->cattle_number, 'items' => $items];*/

            }

        }

        return $findings;

    }
}
