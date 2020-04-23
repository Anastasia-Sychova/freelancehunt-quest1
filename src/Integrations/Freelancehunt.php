<?php
declare(strict_types=1);

namespace Quest1\Integrations;

/**
 * Freelancehunt API
 */
class Freelancehunt
{
    private $token = '0c750fb84bfa0e06ee96a3fc17e12370c119dc2a';
    private $host = 'https://api.freelancehunt.com/v2/';
    private $lastError = [];
    private $skills = [
        99, // Веб-програмування
        1, // PHP
        86, // Бази даних
    ];

    private $groups = [
        [
            'name'     => 'До 500 грн',
            'min'      => 0,
            'max'      => 500,
            'currency' => 'UAH',
        ],
        [
            'name'     => '500-1000 грн',
            'min'      => 501,
            'max'      => 1000,
            'currency' => 'UAH',
        ],
        [
            'name'     => '1000-5000 грн',
            'min'      => 1001,
            'max'      => 5000,
            'currency' => 'UAH',
        ],
        [
            'name'     => 'более 5000 грн',
            'min'      => 5001,
            'max'      => null,
            'currency' => 'UAH',
        ]
    ];

    /**
     * @param int $pageNumber
     *
     * @return \stdClass
     */
    public function getProjects(int $pageNumber = 1): \stdClass
    {
        return $this->httpCall("projects?page[number]=$pageNumber");
    }

    /**
     * @return array
     */
    public function getFilterSkills(): array
    {
        return $this->skills;
    }

    /**
     * @return array
     */
    public function getPieGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return \stdClass
     */
    public function getSkills(): \stdClass
    {
        return $this->httpCall('skills');
    }

    /**
     * Make an HTTP call using curl.
     *
     * @param  string $url       The URL to call
     * @param  string $method    The HTTP method to use, by default GET
     *
     * @return \stdClass
     **/
    private function httpCall(string $url, string $method = 'GET'): \stdClass
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Accept-Language: en',
            "Authorization: Bearer $this->token",
        ];

        $ch = curl_init($this->host . $url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 4096);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $response = curl_exec($ch);

        ## Set HTTP error, if any
        $this->lastError = [
            'code'    => curl_errno($ch),
            'message' => curl_error($ch),
        ];

        return json_decode($response);
    }

    /**
     * Get the last error from curl.
     *
     * @return array Array with 'code' and 'message' indexes
     */
    public function getLastError(): array
    {
        return $this->lastError;
    }
}
