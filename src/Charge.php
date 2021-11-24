<?php

namespace SpeedApps\PaySoon;

use SpeedApps\PaySoon\Exceptions\ChargeException;

class Charge
{
    public $connection;
    protected $charge;
    private  $token;
    private  $whois;

    public function __construct(Connection $connection, string $token, int $whois)
    {
        $this->connection = $connection;
        $this->token = $token;
        $this->whois = $whois;
    }

    public function creditCard(array $dataCharge)
    {
        $dataCharge = $this->setCharge($dataCharge);
        return $this->connection->post('/cartao/pagar', $dataCharge, ['Whois: ' . $this->whois, 'Authorization: Bearer ' . $this->token]);
    }

    public function setCharge(array $charge)
    {
        try {
            $this->charge = array(
                'tipo' => '',
                'valor' => ' ',
                'quantidadeParcelas' => '',
                'dataPagamento' => '',
                'descricaoPagamento' => '',
                'cancelarAntesDeEfetivar' => '',
                'pagador' => [
                    'id' => '',
                    'nome' => '',
                    'tipo' => '',
                    'cpfCnpj' => '',
                    'email' => '',
                    'endereco' => [
                        'id' => '',
                        'logradouro' => '',
                        'numero' => '',
                        'bairro' => '',
                        'cep' => '',
                        'cidade' => '',
                        'complemento' => '',
                        'siglaEstado' => ''
                    ]
                ],
                'cartao' => [
                    'bandeira' => '',
                    'numero' => '',
                    'titular' => '',
                    'codigoSeguranca' => '',
                    'validade' => '',
                    'salvarCartao' => '',
                    'tokenApi' => ''
                ]
            );
            $this->charge = array_merge($this->charge, $charge);
            return $this->charge;
        } catch (ChargeException $e) {
            return 'error generating billing - ' . $e->getMessage();
        }
    }
}
