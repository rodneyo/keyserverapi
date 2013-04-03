<?php
namespace Roles\Model\Apikeys;

class Client
{
    public $id;
    public $apikey;
    public $client_name;
    public $issued_date;
    public $expiration_date;
    public $enabled;
    public $description;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->apikey = (isset($data['apikey'])) ? $data['apikey'] : null;
        $this->client_name =  (isset($data['client_name'])) ? $data['client_name'] : null;
        $this->issued_date = (isset($data['issued_date'])) ? $data['issued_date'] : null;
        $this->expiration_date = (isset($data['expiration_date'])) ? $data['expiration_date'] : null;
        $this->enabled = (isset($data['enabled'])) ? $data['enabled'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
    }
}
