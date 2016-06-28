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
			$examinationObj = new Examination();
			$examinationObj->user_id = $user_id;

			// House
			$house_id = isset($examination['house_id']) ? (int)$examination['house_id'] : null;
			$cattle_number = isset($examination['cattle_number']) ? (int)$examination['cattle_number'] : null;

			if ( $house_id ) {
				$house = House::find($house_id);
			} else if ( $cattle_number ) {
				$house = House::where('cattle_number', $cattle_number)->first();
			}

			if ( !$house && $house_id ) {
				$house = new House([
					'id' => $house_id
					]);
				$house->save();
			} else if ( !$house ) {
				$house = new House([
					'cattle_number' => $cattle_number
					]);
				$house->save();
			}

			// Item
			$item_id = isset($examination['item_id']) ? (int)$examination['item_id'] : null;
			$item_number = isset($examination['item_number']) ? $examination['item_number'] : null;

			if ( $item_id ) {
				$item = Item::find($item_id);
			} else if ( $item_number ) {
				$item = Item::where('item_number', $item_number)->first();
			}

			if ( !$item && $item_id ) {
				$item = new Item();
				$item->id = $item_id;
				if ( isset($examination['collar']) ) {
					$item->collar = $examination['collar'];
				} else {
					$item->collar = null;
				}
				$item->save();
			} else if ( !$item ) {
				$item_data = [
				'item_number' => $item_number
				];
				if ( isset($examination['collar']) ) {
					$item_data['collar'] = $examination['collar'];
				} else {
					$item_data['collar'] = null;
				}
				$item = new Item($item_data);
				$item->save();
			} 

			// Attach item to house
			$house->items()->sync([$item->id], false);

			$examinationObj->item_id = $item->id;
			$examinationObj->save();

			if ( $item && empty($examination['collar']) ) {
				if ( $item->collar ) {
					$item->collar = null;
					$item->save();

					$finding = new Finding([
						'disease_id' => 0,
						'part_id' => 0,
						'subpart_id' => 0,
						'examination_id' => $examinationObj->id,
						'treatment_id' => 0,
						'check_date' => 0,
						'type' => 'Odebrání obojku',
						]);
					$finding->save();
				}
			}

			if ( isset($examination['diseases']) ) {
				foreach( $examination['diseases'] as $disease ) 
				{
					if ( isset($disease['disease_id']) ) {
						$disease_preset = $disease['disease_id'];
					} else {
						$disease_preset = $disease['name'];
					}


					if ( is_array($disease_preset) ) {
						foreach( $disease_preset as $disease_id ) {

							if ( !is_numeric( $disease_id ) ) {
								$name =  $disease_id;
							} else {

								$disease_id = isset($examination['disease_id']) ? (int)$examination['disease_id'] : null;
								$name = isset($examination['name']) ? $examination['name'] : null;
							}

							$diseaseObj = null;

							if ( is_numeric($disease_id) ) {
								$diseaseObj = Disease::find($disease_id);
							} else if ( $disease_id ) {
								$diseaseObj = Disease::where('name', $disease_id)->first();
							}
							
							if ( !$diseaseObj && is_numeric($disease_id) ) {
								$diseaseObj = new Disease([
									'id' => $disease_id
									]);
								$diseaseObj->save();
							} else if ( !$diseaseObj ) {
								$diseaseObj = new Disease([
									'name' => $disease_id
									]);
								$diseaseObj->save();
							}

							if ( is_array($disease['treatment']) ) {

								foreach($disease['treatment'] as $treatment_name) {
									$treatmentObj = Treatment::where('name', $treatment_name)->first();

									if ( !$treatmentObj ) {
										$treatmentObj = new Treatment([
											'name' => $treatment_name
											]);
										$treatmentObj->save();
									}
								}

							} else {

								$treatment_name = $disease['treatment'];

								$treatmentObj = Treatment::where('name', $treatment_name)->first();

								if ( !$treatmentObj ) {
									$treatmentObj = new Treatment([
										'name' => $treatment_name
										]);
									$treatmentObj->save();
								}

							}

							if ( is_array($disease['subpart_id']) ) {
								foreach ( $disease['subpart_id'] as $subpart_id ) {
									$finding = new Finding([
										'disease_id' => $diseaseObj->id,
										'part_id' => $disease['part_id'],
										'subpart_id' => $subpart_id,
										'examination_id' => $examinationObj->id,
										'treatment_id' => $treatmentObj->id,
										'check_date' => $disease['check_date'],
										'type' => $disease['type'],
										]);
									$finding->save();
								}
							} else {

								if ( empty($disease['subpart_id']) ) {
									$disease['subpart_id'] = 0;
								}

								$finding = new Finding([
									'disease_id' => $diseaseObj->id,
									'part_id' => $disease['part_id'],
									'subpart_id' => $disease['subpart_id'],
									'examination_id' => $examinationObj->id,
									'treatment_id' => $treatmentObj->id,
									'check_date' => $disease['check_date'],
									'type' => $disease['type'],
									]);
								$finding->save();
							}

						}
					}
				}
			} 

			if ( !isset($examination['diseases']) || empty($examination['diseases']) ) {
				$finding = new Finding([
					'disease_id' => 0,
					'part_id' => 0,
					'subpart_id' => 0,
					'examination_id' => $examinationObj->id,
					'treatment_id' => 0,
					'check_date' => 0,
					'type' => 'Založení',
					]);
				$finding->save();
			}
		}

		return [
		'success' => true
		];
	}


}
