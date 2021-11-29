<?php

namespace SpeedApps\PaySoon;

use SpeedApps\PaySoon\Exceptions\TransferException;

class Transfer
{
    public $connection;
    protected $transfer;
    private $token;
    private $whois;

    public function __construct(Connection $connection, string $token, int $whois)
    {
        $this->connection = $connection;
        $this->token = $token;
        $this->whois = $whois;
    }

    public function reversal(string $chargeId)
    {
        $dataCharge = $this->setReversal($chargeId);
        return $this->connection->post('/transferencia/solicitar', $this->transfer, ['Whois: ' . $this->whois, 'Authorization: Bearer ' . $this->token]);
    }

    public function setReversal(string $valor, string $type = "cartão")
    {
        try {
            $this->transfer = array(
                "valorSolicitado" => $valor,
                "tipo" => $type
            );;
        } catch (TransferException $e) {
            return 'error transfer - ' . $e->getMessage();
        }
    }
}
