<?php namespace Sanatorium\Hoofmanager\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Sanatorium\Hoofmanager\Models\Disease;

class DiseasesController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/hoofmanager::index');
	}

	public function newDisease()
    {
        $diseases = app('sanatorium.hoofmanager.diseases');

        $diseases->store(null, request()->all());

        return redirect()->back();
    }

    public function edit($id)
    {
        $diseases = app('sanatorium.hoofmanager.diseases');

        $diseases->store($id, request()->all());

        return redirect()->back();
    }

}
