<?php
namespace Roles\Model\Apikeys;

class App
{
    public $id;
    public $client_id;
    public $app_name;
    public $status;
    public $description;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->client_id = (isset($data['client_id'])) ? $data['client_id'] : null;
        $this->app_name =  (isset($data['app_name'])) ? $data['app_name'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
    }
}
