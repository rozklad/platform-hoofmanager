<?php namespace Sanatorium\Hoofmanager\Controllers\Api;

use Input;
use Sanatorium\Hoofmanager\Models\Vet;
use Sanatorium\Hoofmanager\Models\House;
use Sanatorium\Hoofmanager\Models\Finding;
use Sanatorium\Hoofmanager\Models\Examination;
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


        if ($user = Sentinel::authenticate($credentials))
        {
            $this->status = 200;
            $this->result = $this->find($user->id);
            $this->result->diseases = $diseases;
            $this->result->treatments = $treatments;

            $examinations = app('sanatorium.hoofmanager.examination')->where('user_id', $user->id)->get();

            $findings = [];

            foreach ( $examinations as $examination ) {

                $item_number = app('sanatorium.hoofmanager.items')->find($examination->item_id)->item_number;

                $findings[$item_number][] = $examination->findings()->get();

            }

            $this->result->findings = $findings;

            /*$i = 0;

            foreach ( $this->result->houses as $house ) {

                foreach ( $house->items as $item ) {

                    foreach ( $item->examinations()->orderBy('created_at', 'DESC')->get() as $examination ) {

                        foreach ( $examination->findings()->get() as $finding ) {

                            $findings_data = $item->findings_data;

                            if ( is_object($finding->disease()->first()) ) {

                                $disease_name = $finding->disease()->first()->name;

                            } else {

                                $disease_name = 'Bez nálezu';

                            }

                            if ( is_object($finding->treatment()->first()) ) {

                                $treatment_name = $finding->treatment()->first()->name;

                            } else {

                                $treatment_name = 'Bez ošetření';

                            }

                            if ( is_object($finding->part()->first()) ) {

                                $part_name = $finding->part()->first()->name;

                            } else {

                                $part_name = '';

                            }

                            $findings_data[$i]['item_number'] = $item->item_number;

                            $findings_data[$i]['findings'][$i]['disease'] = $disease_name;

                            $findings_data[$i]['findings'][$i]['treatment'] = $treatment_name;

                            $findings_data[$i]['findings'][$i]['part'] = $part_name;

                            $findings_data[$i]['findings'][$i]['check_date'] = $finding->check_date;

                            $findings_data[$i]['findings'][$i]['type'] = $finding->type;

                            $i++;

                        }

                    }

                }

            }

            $this->result->findings = $findings_data;*/

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

        $houses = [];

        $examinations = app('sanatorium.hoofmanager.examination')->where('user_id', $vet->id)->get();

        $findings = [];

        foreach ( $examinations as $examination ) {

            $item_number = app('sanatorium.hoofmanager.items')->find($examination->item_id)->item_number;

            $findings[$item_number][] = $examination->findings()->get();

        }

        if ( $vet->isAdmin() ) {

            foreach( House::all() as $house ) {
                $houses[$house->id] = $house;
            }

        } else {

            foreach( House::all()->where('user_id', $vet->id) as $house ) {
                $houses[$house->id] = $house;
            }

        }

        $vet->houses = $houses;
        $vet->findings = $findings;

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
