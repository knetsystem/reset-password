<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Knet extends Model
{
    protected $ch;
    protected $headers;
    protected $apikey;

    public function __construct($apikey = '')
    {
        // Check if libcurl is enabled
        if (!function_exists('curl_init')) {
            exit("ERROR: Please enable php-curl\n");
        }

        // API key from .env or directly
        $this->apikey = ($apikey == '') ? env('KNET_API_KEY') : $apikey;

        // Public cURL handle (we want to reuse connections)
        $this->ch = curl_init();
        $this->headers = [
            'authorization' => 'Authorization: Basic '.base64_encode($this->apikey),
            'content_type'  => 'Content-Type: application/json',
            'accept'        => 'Accept: application/json',
        ];
    }

    protected function httpHeaders($o = [])
    {
        $ret = $this->headers;
        if (isset($o['headers'])) {
            foreach ($o['headers'] as $key => &$val) {
                $ret[strtolower($key)] = $key.': '.$val;
            }
        }

        return array_values($ret);
    }

    protected function request($path, $opts = [], $data = null)
    {
        $hostname = (isset($opts['hostname'])) ? $opts['hostname'] : 'api.k-net.dk';
        $curlopts = [
            CURLOPT_URL            => 'https://'.$hostname.$path,
            CURLOPT_HTTPHEADER     => $this->httpHeaders($opts),
            CURLOPT_CUSTOMREQUEST  => ($data === null) ? 'GET' : 'PATCH',
            CURLOPT_POST           => ($data === null) ? false : true,
            CURLOPT_POSTFIELDS     => ($data === null) ? null : json_encode($data),
            CURLOPT_VERBOSE        => isset($opts['debug']) ? $opts['debug'] : 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_USE_SSL        => CURLUSESSL_ALL,
            CURLOPT_SSLVERSION     => 6, // TLSv1.2
        ];
        // Allow override of $curlopts.
        if (isset($opts['curl'])) {
            foreach ($opts['curl'] as $key => &$val) {
                $curlopts[$key] = $val;
            }
        }
        curl_setopt_array($this->ch, $curlopts);

        $result = curl_exec($this->ch);
        if (!$result) {
            throw new \Exception(curl_strerror(curl_errno($this->ch)));
        }

        $statusCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        if ($statusCode !== 200) {
            throw new \Exception(explode("\n", $result)[0]);
        }
        // Decode the json response (@: surpress warnings)
        if (!$resobj = @json_decode($result, true)) {
            throw new \Exception('Invalid response from server');
        }

        return $resobj;
    }

    public function findByEmail($email) // September 2021: email field removed so we only search the username field
    {
        $email = strtolower($email);

        $users = $this->request('/v2/network/user/?username='.$email)['results'];

        $matches = [];

        foreach ($users as $key => $value) {
            if (strtolower($value['username']) == $email) {
                $matches[] = $key;
            }
        }

        if (count($matches) > 1) {
            throw new \Exception('More than one entry found. Must be unique');
        }

        if (isset($matches[0])) {
            return $users[$matches[0]];
        }
    }

    public function patchUser($url, $password = '')
    {
        // Check url format, exception if wrong
        if (!preg_match('/^https:\/\/api.k-net\.dk\/v2\/network\/user\/[0-9]{1,}\/$/', $url)) {
            throw new \Exception('Url format is not a K-net user.');
        }

        // Begind data array
        $data = [];

        // If new password, set a new password with the password setter
        if ($password != '') {
            $data['password_setter'] = $password;
        }

        // Extract local part
        preg_match('/\/v2\/network\/user\/[0-9]{1,}\/$/', $url, $local);

        // Send patch request
        $o = $this->request($local[0], [], $data);

        // Confirm password was changed

        // Parse password string
        $password_parts = explode('$', $o['password']);

        // Check hash type
        if ($password_parts[0] != 'sha1') {
            throw new \Exception('Error patching password in user data. Unexpeted hash type.');
        }

        // Check password is set correctly
        if ($password_parts[2] != hash('sha1', $password_parts[1].$password, false)) {
            throw new \Exception('Error patching password in user data. Password was not set correctly.');
        }

        // Confirm
        return true;
    }
}
