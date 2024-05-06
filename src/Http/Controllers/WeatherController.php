<?php

namespace Stepanenko3\NovaCards\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherController
{
    public function __invoke(
        Request $request,
    ) {
        $data = Cache::remember(
            key: 'nova-weather-card:' . implode('-', [
                $request->input('q', 'Kiev'),
                $request->input('units', 'metric'),
                $request->input('lang', config('app.locale')),
            ]),
            ttl: 10,
            callback: fn () => Http::get(
                url: 'https://api.openweathermap.org/data/2.5/weather',
                query: [
                    'q' => $request->input('q', 'Kiev'),
                    'appid' => config('nova-cards.open_weather_api_key'),
                    'units' => $request->input('units', 'metric'),
                    'lang' => $request->input('lang', config('app.locale')),
                ],
            )->json(),
        );

        return response()->json($data);
    }
}
