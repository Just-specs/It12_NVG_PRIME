<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CalendarificService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct(?string $apiKey = null, ?string $baseUrl = null)
    {
        $this->apiKey = $apiKey ?? (string) config('services.calendarific.key');
        $this->baseUrl = rtrim($baseUrl ?? (string) config('services.calendarific.base', 'https://calendarific.com/api/v2'), '/');
    }

    /**
     * Fetch holidays for a country and year.
     *
     * @throws RequestException
     */
    public function holidays(string $country, int $year, ?string $type = null): Collection
    {
        $response = Http::timeout(10)
            ->acceptJson()
            ->get("{$this->baseUrl}/holidays", array_filter([
                'api_key' => $this->apiKey,
                'country' => $country,
                'year'    => $year,
                'type'    => $type,
            ]));

        $response->throw();

        return collect($response->json('response.holidays', []));
    }
}
