// app/Services/DigiTapAadharService.php
namespace App\Services;

use GuzzleHttp\Client;

class DigiTapAadharService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.digitap_aadhar.api_key');
    }

    public function submitAadharData(array $data)
    {
        $url = 'https://api.digitap-aadhar.com/submit'; // Example URL, replace with the actual DigiTap Aadhar API endpoint

        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);

        return json_decode($response->getBody(), true);
    }
}
