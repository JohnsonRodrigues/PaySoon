<?php

namespace SpeedApps\PaySoon;

use SpeedApps\PaySoon\Exceptions\ReversalException;

class Reversal
{
    public  $connection;
    protected $reversal;
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
        return $this->connection->post('/cartao/pagar', $this->reversal, ['Whois: ' . $this->whois, 'Authorization: Bearer ' . $this->token]);
    }

    public function setReversal(string $chargeId)
    {
        try {
            $this->reversal = array(
                'codigoVenda' => $chargeId,
                'codigoCielo' => '',
                'numeroAutorizacao' => '',
                'nsuMaquina' => ''
            );;
        } catch (ReversalException $e) {
            return 'error reversal purchase - ' . $e->getMessage();
        }
    }
}
