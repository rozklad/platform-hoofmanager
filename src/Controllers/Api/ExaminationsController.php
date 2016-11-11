<?php namespace Sanatorium\Hoofmanager\Controllers\Api;

use Sanatorium\Hoofmanager\Models\Disease;
use Sanatorium\Hoofmanager\Models\House;
use Sanatorium\Hoofmanager\Models\Item;
use Sanatorium\Hoofmanager\Models\Examination;
use Sanatorium\Hoofmanager\Models\Finding;
use Sanatorium\Hoofmanager\Models\Treatment;
use Input;

class ExaminationsController extends ApiController {


	/**
	 * Show the form for creating new houses.
	 *
	 * @return \Illuminate\View\View
	 */
	public function store()
	{
		extract(Input::all());

		foreach ( $examinations as $examination )
		{
		    /* TODO better */
            $item_id = app('sanatorium.hoofmanager.items')->where('item_number', $examination['item_number'])->first()->id;

			$examinationObj = new Examination();
			$examinationObj->user_id = $user_id;
            $examinationObj->item_id = $item_id;
            $examinationObj->created_at = $examination['created_at'];
            $examinationObj->save();

            $finding = $examination['diseases'][0];

            $finding = new Finding([
                'disease_id' => $finding['disease_id'],
                'part_id' => $finding['part_id'],
                'subpart_id' => $finding['subpart_id'],
                'examination_id' => $examinationObj->id,
                'treatment_id' => $finding['treatment_id'],
                'check_date' => $finding['check_date'],
                'type' => $finding['type'],
            ]);
            $finding->save();


		}

		return [
		'success' => true
		];
	}


}
