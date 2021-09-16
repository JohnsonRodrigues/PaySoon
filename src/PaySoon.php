<?php
declare(strict_types=1);

namespace SpeedApps\PaySoon;

class PaySoon
{
    private Charge $charge;
    private string $token;
    private int $whois;

    public function __construct(string $user, string $password, string $type = 'homologation')
    {
        $connection = new \SpeedApps\PaySoon\Connection("usuario", "usuario", "homologation");
        $data = $connection->authorization();
        $this->whois = $data['whois'];
        $this->token = $data['token'];
        $this->setCharge(new Charge($connection, $this->token, $this->whois));
    }

    public function getCharge(): \SpeedApps\PaySoon\Charge
    {
        return $this->charge;
    }

    public function setCharge(\SpeedApps\PaySoon\Charge $charge): void
    {
        $this->charge = $charge;
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
