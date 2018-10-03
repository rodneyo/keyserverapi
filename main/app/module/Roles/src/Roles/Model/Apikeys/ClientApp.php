<?php
namespace Roles\Model\Apikeys;

class ClientApp
{
    public $id;
    public $client_id;
    public $app_id;
    public $enabled;
    public $name;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->client_id =  (isset($data['client_id'])) ? $data['client_id'] : null;
        $this->app_id = (isset($data['app_id'])) ? $data['app_id'] : null;
        $this->enabled = (isset($data['enabled'])) ? $data['enabled'] : null;
    }
}
