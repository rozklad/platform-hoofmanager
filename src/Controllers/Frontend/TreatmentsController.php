<?php namespace Sanatorium\Hoofmanager\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class TreatmentsController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/hoofmanager::index');
	}

	public function newTreatment()
    {

        $treatments = app('sanatorium.hoofmanager.treatment');

        $treatments->store( null, request()->all() );

        return redirect()->back();
    }

    public function edit($id)
    {
        $data = request()->all();

        if ( ! request()->fasy_vyrobek ) {
            $data['fasy_vyrobek'] = 0;
        }

        $treatments = app('sanatorium.hoofmanager.treatment');

        $treatments->store( $id, $data );

        return redirect()->back();
    }

}
