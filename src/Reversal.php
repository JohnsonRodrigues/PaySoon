<?php

namespace SpeedApps\PaySoon;

use SpeedApps\PaySoon\Exceptions\ReversalException;

class Reversal
{
    public Connection $connection;
    protected array $reversal;
    private string $token;
    private int $whois;

    public function __construct(Connection $connection, string $token, int $whois)
    {
        $this->connection = $connection;
        $this->token = $token;
        $this->whois = $whois;
    }

    public function reversal(string $chargeId)
    {
        $dataCharge = $this->setReversal($chargeId);
        return $this->connection->post('/cartao/pagar', $dataCharge, ['Whois: ' . $this->whois, 'Authorization: Bearer ' . $this->token]);
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
            return 'error generating billing - ' . $e->getMessage();
        }
    }
}
