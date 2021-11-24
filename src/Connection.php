<?php
declare(strict_types=1);

namespace SpeedApps\PaySoon;

use SpeedApps\PaySoon\Exceptions\AuthenticateException;
use SpeedApps\PaySoon\Exceptions\ConnectionException;

class Connection
{
    private $user;
    private $password;
    private $type;
    private $urlType;
    private $base_url;
    private $headers;

    public function __construct(string $user, string $password, string $type = 'homologation')
    {
        $this->user = $user;
        $this->password = $password;
        if ($type == 'production') {
            $this->urlType = false;
        } elseif ($type == 'homologation') {
            $this->urlType = true;
        } else {
            die('Type of invalid approval');
        }
        $this->base_url = "https://" . (($this->urlType) ? 'sandbox' : 'www');
    }

    public function authorization()
    {
        try {
            $params = [
                "usuario" => $this->user,
                "senha" => $this->password
            ];
            $response = $this->post('/auth', $params);

            if (empty($response->data) && isset($response->responseStatus) && $response->responseStatus->status === 'error')
                throw new AuthenticateException("Incorrect username and/or password(s).");

            return [
                'whois' => $response->data->whois ?? null,
                'token' => $response->data->token ?? null
            ];
        } catch (ConnectionException | AuthenticateException $e) {
            die($e);
        }
    }

    public function get(string $url, bool $option = false, bool $custom = false, array $headers = array())
    {
        array_push($headers, "Content-Type: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->base_url . '.paguelogo.com.br/api' . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        if (!empty($custom)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $custom);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);

        if (empty($response))
            throw new ConnectionException('Connection Not Found');
        return $response;
    }

    public function post(string $url = '/', array $params = [], array $headers = array())
    {
        array_push($headers, "Content-Type: application/json");
        $params = json_encode($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->base_url . '.paguelogo.com.br/api' . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        if (empty($response))
            throw new ConnectionException('Connection Not Found');
        return $response;
    }
}
