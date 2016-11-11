<?php namespace Sanatorium\Hoofmanager\Handlers\Items;

class ItemsDataHandler implements ItemsDataHandlerInterface {

    /**
     * {@inheritDoc}
     */
    public function prepare(array $data)
    {

        if ( isset($data['pivot']) )
            unset($data['pivot']);

        if ( isset($data['values']) )
            unset($data['values']);

        return $data;
    }

}
