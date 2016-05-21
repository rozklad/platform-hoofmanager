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

		$houses = House::where('user_id', $vet->id)->get();

		/*$houses = $vet->with('examinations.item.houses')->get();

		$house_ids = $houses->lists('id');

		$houses = House::whereIn('id', $house_ids)->get();*/

		return view('sanatorium/hoofmanager::index', compact('examinations', 'houses'));
	}

	public function start()
	{
		return view('sanatorium/hoofmanager::start');
	}

	public function animals()
	{
		$vet = Vet::getVet();

		/*$houses = $vet->with('examinations.item.houses')->get();

		$houses_ids = $houses->lists('id');

		$houses = House::whereIn('id', $houses_ids)->get();*/

		$houses = House::where('user_id', $vet->id)->get();

		$findings = app('sanatorium.hoofmanager.finding');

		$examinations = app('sanatorium.hoofmanager.examination');

		$tests = $examinations->where('item_id', 49)->get();

		$pole = [];

		foreach ( $tests as $test ) {
			array_push($pole, $findings->where('examination_id', $test->id)->get());
		}

		/*dd($pole);

		dd($findings->where('examination_id', $test->id)->get());*/

		return view('sanatorium/hoofmanager::animals', compact('houses', 'vet'));
	}

	public function plan()
	{

		$checks = app('sanatorium.hoofmanager.finding')->whereNotNull('check_date')->where('check_date', '!=' , '0000-00-00 00:00:00')->orderBy('check_date', 'ASC')->get();

		$examinations = app('sanatorium.hoofmanager.examination')->get();

		$plans = [];

		for ( $i=0; $i < count($checks); $i++ )
		{
			array_push($plans, $examinations->where('id', $checks[$i]->examination_id)->first());
		}

		$houses = app('sanatorium.hoofmanager.houses')->get();

		return view('sanatorium/hoofmanager::plan', compact('plans', 'houses'));
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

		$findings = app('sanatorium.hoofmanager.finding')->findAll();

		$diseases = app('sanatorium.hoofmanager.diseases')->findAll();

		$houses = app('sanatorium.hoofmanager.houses')->findAll();

		$items = app('sanatorium.hoofmanager.items')->findAll();

		$vet = Vet::getVet();

		// @TODO: better exceptions
		if ( !is_object($vet) )
			return null;

		$examinations = $vet->examinations()->orderBy('created_at', 'DESC')->get();

		$counts = [];

		$names = [];

		for ( $i = 0; $i < count($diseases); $i++ )
		{
			if ( count($findings->where('disease_id', $diseases[$i]->id)) > 10 ) {

				array_push($counts, count($findings->where('disease_id', $diseases[$i]->id)));

				array_push($names, $diseases[$i]->name);

			}
		}

		return view('sanatorium/hoofmanager::stats', compact('counts', 'names', 'findings', 'houses', 'items', 'examinations'));
	}

}
