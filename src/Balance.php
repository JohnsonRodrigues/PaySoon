<?php

namespace SpeedApps\PaySoon;

use SpeedApps\PaySoon\Exceptions\BalanceException;
use SpeedApps\PaySoon\Exceptions\ReversalException;

class Balance
{
    public $connection;
    protected $reversal;
    private $token;
    private $whois;

    public function __construct(Connection $connection, string $token, int $whois)
    {
        $this->connection = $connection;
        $this->token = $token;
        $this->whois = $whois;
    }

    public function accountBalance(string $type = "cartao")
    {
        try {
            if ($type == 'cartao' || $type == "boleto"){
                return $this->connection->post('resumoFinanceiro/saldoDisponivel?tipo=' . $type, null, ['Whois: ' . $this->whois, 'Authorization: Bearer ' . $this->token]);
            }
            Throw new BalanceException("o tipo de consulta permitido é cartão ou boleto");
        }catch (BalanceException $e){
            return 'Saldo em conta:  - ' . $e->getMessage();
        }
    }
}
