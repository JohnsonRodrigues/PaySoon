<?php

namespace SpeedApps\PaySoon;

use SpeedApps\PaySoon\Exceptions\TransferException;

class Transfer
{
    public $connection;
    private $token;
    private $whois;

    public function __construct(Connection $connection, string $token, int $whois)
    {
        $this->connection = $connection;
        $this->token = $token;
        $this->whois = $whois;
    }

    public function transfer(array $data = [])
    {
        return $this->connection->post('/transferencia/solicitar', $data, ['Whois: ' . $this->whois, 'Authorization: Bearer ' . $this->token]);
    }

}
