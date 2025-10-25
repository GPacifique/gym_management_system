<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $driver;
    protected ?string $twilioSid;
    protected ?string $twilioToken;
    protected ?string $twilioFrom;
    protected Client $http;

    public function __construct(?Client $http = null)
    {
        $this->driver = config('sms.driver', 'log');
        $this->twilioSid = config('sms.twilio.sid');
        $this->twilioToken = config('sms.twilio.token');
        $this->twilioFrom = config('sms.twilio.from');
        $this->http = $http ?? new Client(['timeout' => 10]);
    }

    public function send(string $to, string $message): bool
    {
        if ($this->driver === 'twilio') {
            return $this->sendViaTwilio($to, $message);
        }

        // Default: log the SMS for development/testing
        Log::info('[SMS LOG]', ['to' => $to, 'message' => $message]);
        return true;
    }

    protected function sendViaTwilio(string $to, string $message): bool
    {
        if (!$this->twilioSid || !$this->twilioToken || !$this->twilioFrom) {
            Log::warning('Twilio SMS not configured. Falling back to log.', [
                'sid' => (bool) $this->twilioSid,
                'token' => (bool) $this->twilioToken,
                'from' => (bool) $this->twilioFrom,
            ]);
            Log::info('[SMS LOG]', ['to' => $to, 'message' => $message]);
            return true;
        }

        try {
            $endpoint = sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json', $this->twilioSid);
            $response = $this->http->post($endpoint, [
                'auth' => [$this->twilioSid, $this->twilioToken],
                'form_params' => [
                    'To' => $to,
                    'From' => $this->twilioFrom,
                    'Body' => $message,
                ],
            ]);

            $status = $response->getStatusCode();
            if ($status >= 200 && $status < 300) {
                return true;
            }

            Log::error('Twilio SMS failed', ['status' => $status, 'to' => $to]);
            return false;
        } catch (\Throwable $e) {
            Log::error('Twilio SMS exception', [
                'message' => $e->getMessage(),
                'to' => $to,
            ]);
            return false;
        }
    }
}
