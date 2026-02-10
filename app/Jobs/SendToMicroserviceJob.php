<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Jobs\SaveApiLogJob;

class SendToMicroserviceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3; // max retries
    public int $timeout = 30; // seconds per attempt

    protected string $url;
    protected array $payload;
    protected array $headers;
    protected string $jwtToken;

    protected array $meta;


    public function __construct(string $url, array $payload, array $headers = [], string $jwtToken, $meta)
    {
        $this->url = $url;
        $this->payload = $payload;
        $this->meta = $meta;

        $this->headers = $headers;
        $this->jwtToken = $jwtToken;
    }

    public function handle(): void
    {
        $client = new Client([
            'timeout' => $this->timeout,
        ]);

        $data =  [
            'headers' => array_merge([
                'Authorization' => 'Bearer ' . $this->jwtToken,
                'Accept' => 'application/json',
            ], $this->headers),

            'json' => $this->payload,
        ];

        $logData = [
            'ip' =>  $this->meta['ip'],
            'user_agent' =>  $this->meta['user_agent'],
            'method' =>  $this->meta['method'],
            'url' =>  $this->meta['url'],
            'request_body' =>   json_encode($data),
            'response_body' =>  json_encode($data),
            'status_code' => 200,
            'user_id' => optional(auth()->user())->id,
        ];

        try {

            // QUEUE DISPATCH

            $response = $client->post($this->url,  $data);

            $logData["response_body"] =$response->getBody()->getContents();
            $logData["status_code"] = $response->getStatusCode();

            SaveApiLogJob::dispatch($logData);

        } catch (RequestException $e) {
            // Retry if server error (5xx)
            if ($e->getCode() >= 500 && $this->attempts() < $this->tries) {

                $logData["response_body"] = 'Retrying microservice request due to server error';
                $logData["status_code"] = 500;
                SaveApiLogJob::dispatch($logData);

                // Throw exception to trigger queue retry
                throw $e;
            }


            $logData["response_body"] = 'Microservice request failed. ' . $e->getMessage();
            $logData["status_code"] = 501;
            SaveApiLogJob::dispatch($logData);
        }
    }

   
}
