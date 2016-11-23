<?php namespace Sanatorium\Hoofmanager\Controllers\Api;

use Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepositoryInterface;
use Sanatorium\Hoofmanager\Repositories\Finding\FindingRepositoryInterface;
use Sanatorium\Hoofmanager\Repositories\Items\ItemsRepositoryInterface;

use Input;

class FindingsController extends ApiController {

    /**
     * {@inheritDoc}
     */
    protected $csrfWhitelist = [
        'executeAction',
    ];

    /**
     * The Hoofmanager repository.
     *
     * @var \Sanatorium\Hoofmanager\Repositories\Finding\FindingsRepositoryInterface
     */
    protected $findings;

    /**
     * Holds all the mass actions we can execute.
     *
     * @var array
     */
    protected $actions = [
        'delete',
        'enable',
        'disable',
    ];

    /**
     * Constructor.
     *
     * @param  \Sanatorium\Hoofmanager\Repositories\Finding\FindingRepositoryInterface  $findings
     * @param  \Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepositoryInterface $apilogs
     * @param  \Sanatorium\Hoofmanager\Repositories\Items\ItemsRepositoryInterface $items
     * @return void
     */
    public function __construct(ApilogRepositoryInterface $apilogs,
                                FindingRepositoryInterface $findings,
                                ItemsRepositoryInterface $items)
    {
        parent::__construct($apilogs);

        $this->findings = $findings;

        $this->apilogs = $apilogs;

        $this->items = $items;
    }

    /**
     * Datasource for the findings Data Grid.
     *
     * @return \Cartalyst\DataGrid\DataGrid
     */
    public function grid()
    {
        $data = $this->diseases->grid();

        $columns = [
            'id',
            'user_id',
            'item_id',
            'disease_id',
            'part_id',
            'subpart_id',
            'treatment_id',
            'check_date',
            'type',
            'created_at',
        ];

        $settings = [
            'sort'      => 'created_at',
            'direction' => 'desc',
        ];

        $transformer = function($element)
        {
            $element->edit_uri = route('admin.sanatorium.hoofmanager.findings.edit', $element->id);

            return $element;
        };

        return datagrid($data, $columns, $settings, $transformer);
    }

    public function simple()
    {
        $cols = Input::has('cols') ? Input::get('cols') : ['id', 'name','infectious'];

        $data = $this->diseases->all();

        $result = [];

        foreach( $data as $item ) {
            $result_item = [];

            foreach( $cols as $col ) {
                $result_item[$col] = $item->{$col};
            }

            $result[] = $result_item;
        }

        return $result;
    }

    /**
     * Show the form for creating new diseases.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return $this->showForm('create');
    }

    /**
     * Handle posting of the form for creating new diseases.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        return $this->processForm('create');
    }

    /**
     * Show the form for updating diseases.
     *
     * @param  int  $id
     * @return mixed
     */
    public function edit($id)
    {
        return $this->showForm('update', $id);
    }

    /**
     * Handle posting of the form for updating diseases.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        return $this->processForm('update', $id);
    }

    /**
     * Remove the specified diseases.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $type = $this->diseases->delete($id) ? 'success' : 'error';

        $this->alerts->{$type}(
            trans("sanatorium/hoofmanager::diseases/message.{$type}.delete")
        );

        return redirect()->route('admin.sanatorium.hoofmanager.diseases.all');
    }

    /**
     * Executes the mass action.
     *
     * @return \Illuminate\Http\Response
     */
    public function executeAction()
    {
        $action = request()->input('action');

        if (in_array($action, $this->actions))
        {
            foreach (request()->input('rows', []) as $row)
            {
                $this->diseases->{$action}($row);
            }

            return response('Success');
        }

        return response('Failed', 500);
    }

    /**
     * Shows the form.
     *
     * @param  string  $mode
     * @param  int  $id
     * @return mixed
     */
    protected function showForm($mode, $id = null)
    {
        // Do we have a diseases identifier?
        if (isset($id))
        {
            if ( ! $diseases = $this->diseases->find($id))
            {
                $this->alerts->error(trans('sanatorium/hoofmanager::diseases/message.not_found', compact('id')));

                return redirect()->route('admin.sanatorium.hoofmanager.diseases.all');
            }
        }
        else
        {
            $diseases = $this->diseases->createModel();
        }

        // Show the page
        return view('sanatorium/hoofmanager::diseases.form', compact('mode', 'diseases'));
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
        $data = request()->all();

        $item = app('sanatorium.hoofmanager.items')->where('item_number', $data['item_id'])->first();

        $item_id = $item->id;

        $data['item_id'] = $item_id;

        if ( $data['disease_id'] == -1 )
            $data['disease_id'] = 0;

        if ( $data['treatment_id'] == -1 )
            $data['treatment_id'] = 0;

        if ( isset($data['sick']) )
            list($messages, $item_stored) = $this->items->store($item_id, ['sick' => $data['sick']]);
            unset($data['sick']);

        // Store the finding
        list($messages, $finding) = $this->findings->store($id, $data);

        // Do we have any errors?
        if ($messages->isEmpty())
        {
            return $finding;
        }

        return ['success' => false];
    }

}
