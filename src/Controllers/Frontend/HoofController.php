<?php namespace Sanatorium\Hoofmanager\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

use Sanatorium\Hoofmanager\Models\House;
use Sanatorium\Hoofmanager\Models\Item;
use Sanatorium\Hoofmanager\Models\Finding;
use Sanatorium\Hoofmanager\Models\Disease;
use Sanatorium\Hoofmanager\Models\Treatment;
use Sanatorium\Hoofmanager\Models\Vet;
use Sanatorium\Hoofmanager\Models\Examination;
use PDF;

use Sentinel;

class HoofController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		if ( !Sentinel::check() )
			return redirect()->route('user.login');

		$vet = Vet::getVet();

		$examinations = $vet->examinations()->orderBy('created_at', 'DESC')->get();

		if ( $vet->isAdmin() ) {

			$houses = House::all();

		} else {

			$houses = House::where('user_id', $vet->id)->get();

		}

		return view('sanatorium/hoofmanager::index', compact('examinations', 'houses', 'vet'));
	}

	public function start()
	{

	    $vet = Vet::getVet();

		return view('sanatorium/hoofmanager::start', compact('vet'));
	}

	public function animals()
	{

		if ( !Sentinel::check() )
			return redirect()->route('user.login');

		$vet = Vet::getVet();

		if ( $vet->isAdmin() ) {

			$houses = House::all();

		} else {

			$houses = House::where('user_id', $vet->id)->get();

		}

		$findings = app('sanatorium.hoofmanager.finding');

		$examinations = app('sanatorium.hoofmanager.examination');

		$items = app('sanatorium.hoofmanager.items')->get();

		return view('sanatorium/hoofmanager::animals', compact('houses', 'vet', 'items'));

	}

	public function plan()
	{

		$checks = app('sanatorium.hoofmanager.finding')->whereNotNull('check_date')->where('check_date', '!=' , '0000-00-00 00:00:00')->orderBy('check_date', 'ASC')->get();

		$examinations = app('sanatorium.hoofmanager.examination')->get();

        if ( !Sentinel::check() )
            return redirect()->route('user.login');

        $vet = Vet::getVet();

		$plans = [];

		for ( $i=0; $i < count($checks); $i++ )
		{
			array_push($plans, $examinations->where('id', $checks[$i]->examination_id)->first());
		}

		$houses = app('sanatorium.hoofmanager.houses')->get();

		return view('sanatorium/hoofmanager::plan', compact('plans', 'houses', 'vet'));
	}

	public function pdfPlanAll()
	{
		$checks = app('sanatorium.hoofmanager.finding')->whereNotNull('check_date')->where('check_date', '!=' , '0000-00-00 00:00:00')->orderBy('check_date', 'ASC')->get();

		$examinations = app('sanatorium.hoofmanager.examination')->get();

		$plans = [];

		for ( $i=0; $i < count($checks); $i++ )
		{
			array_push($plans, $examinations->where('id', $checks[$i]->examination_id)->first());
		}

		// @TODO: better exceptions
		if ( !class_exists('PDF') )
			return null;

		$pdf = PDF::loadView('pdf.plan', compact('plans'));

		return $pdf->stream('plan.pdf');
	}

	public function pdfPlanSingleHouse($id)
	{
		$checks = app('sanatorium.hoofmanager.finding')->whereNotNull('check_date')->where('check_date', '!=' , '0000-00-00 00:00:00')->orderBy('check_date', 'ASC')->get();

		$examinations = app('sanatorium.hoofmanager.examination')->get();

		$plans = [];

		for ( $i=0; $i < count($checks); $i++ )
		{
			array_push($plans, $examinations->where('id', $checks[$i]->examination_id)->first());
		}

		// @TODO: better exceptions
		if ( !class_exists('PDF') )
			return null;

		$pdf = PDF::loadView('pdf.single', compact('plans', 'id'));

		return $pdf->stream('single.pdf');

	}

	public function stats()
	{

		if ( !Sentinel::check() )
			return redirect()->route('user.login');

		$vet = Vet::getVet();

		$diseases = app('sanatorium.hoofmanager.diseases')->findAll();

		if ( $vet->isAdmin() ) {

			$houses = House::all();

			$items = Item::all();

		} else {

			$houses = House::where('user_id', $vet->id)->get();

			$items = Item::where('user_id', $vet->id)->get();

		}

		// @TODO: better exceptions
		if ( !is_object($vet) )
			return null;

		if ( $vet->isAdmin() ) {

			$examinations = Examination::all();

			$findings = Finding::all();

		} else {

			$examinations = $vet->examinations()->orderBy('created_at', 'DESC')->get();

			$findings = Finding::whereHas('examination', function($q)  use ($vet) {

				return $q->where('user_id', $vet->id);

			})->get();

		}

		$names = [];

		$counts = [];

		for ( $i = 0; $i < count($diseases); $i++ )
		{
			if ( count($findings->where('disease_id', $diseases[$i]->id)) != 0 ) {

				array_push($counts, count($findings->where('disease_id', $diseases[$i]->id)));

				array_push($names, $diseases[$i]->name);

			}
		}

		return view('sanatorium/hoofmanager::stats', compact('counts', 'names', 'findings', 'houses', 'items', 'examinations', 'vet'));
	}

	public function statsByHouse() 
	{

		$houseid = request()->id;

		$diseases = app('sanatorium.hoofmanager.diseases')->findAll();

		$findingByHouse = Finding::whereHas('examination', function($q) use ($houseid) {

			return $q->whereHas('item', function($q) use ($houseid) {

				return $q->whereHas('houses', function($q) use ($houseid) {

					return $q->where('houses.id', $houseid);

				});

			});

		})->get();

		$names = [];

		$counts = [];

		for ( $i = 0; $i < count($diseases); $i++ )
		{
			if ( count($findingByHouse->where('disease_id', $diseases[$i]->id)) != 0 ) {

				array_push($counts, count($findingByHouse->where('disease_id', $diseases[$i]->id)));

				array_push($names, $diseases[$i]->name);

			}
		}

		$data[0]['names'] = $names;
		$data[0]['counts'] = $counts;

		return $data;

	}

    public function diseasesAndTreatments()
    {
        $vet = Vet::getVet();

        $diseases = app('sanatorium.hoofmanager.diseases')->get();

        $treatments = app('sanatorium.hoofmanager.treatment')->get();

        return view('sanatorium/hoofmanager::diseasesandtreatments/index', compact('vet', 'diseases', 'treatments'));
    }

}