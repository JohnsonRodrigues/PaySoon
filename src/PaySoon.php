<?php
declare(strict_types=1);

namespace SpeedApps\PaySoon;

class PaySoon
{
    private $charge;
    private $reversal;
    private $transfer;
    private $balance;
    private $token;
    private $whois;

    public function __construct(string $user, string $password, string $type = 'homologation')
    {
        $connection = new \SpeedApps\PaySoon\Connection($user, $password, $type);
        $data = $connection->authorization();
        $this->whois = $data['whois'];
        $this->token = $data['token'];
        $this->setCharge(new Charge($connection, $this->token, $this->whois));
        $this->setReversal(new Reversal($connection, $this->token, $this->whois));
        $this->setTransfer(new Transfer($connection, $this->token, $this->whois));
        $this->setBalance(new Balance($connection, $this->token, $this->whois));
    }

    public function getReversal(): Reversal
    {
        return $this->reversal;
    }

    public function setReversal(Reversal $reversal): void
    {
        $this->reversal = $reversal;
    }

    public function getCharge(): \SpeedApps\PaySoon\Charge
    {
        return $this->charge;
    }

    public function setCharge(\SpeedApps\PaySoon\Charge $charge): void
    {
        $this->charge = $charge;
    }

    public function getTransfer(): \SpeedApps\PaySoon\Transfer
    {
        return $this->transfer;
    }

    public function setTransfer(\SpeedApps\PaySoon\Transfer $transfer): void
    {
        $this->transfer = $transfer;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setBalance($balance): void
    {
        $this->balance = $balance;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getWhois(): int
    {
        return $this->whois;
    }

    public function setWhois(int $whois): void
    {
        $this->whois = $whois;
    }
}
