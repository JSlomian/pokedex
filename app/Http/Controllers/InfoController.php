<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\InfoRequest;
use App\Models\BannedPokemon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

final class InfoController extends Controller
{
    public function __invoke(InfoRequest $request): JsonResponse
    {
        $names = collect($request->validated()['pokemons'])
            ->map(fn (string $n) => str($n)->trim()->lower()->toString())
            ->filter()
            ->unique()
            ->values();

        if ($names->isEmpty()) {
            return response()->json([]);
        }

        $banned = BannedPokemon::whereIn('name', $names)->pluck('name');
        $allowed = $names->diff($banned)->values();

        if ($allowed->isEmpty()) {
            return response()->json([]);
        }

        $responses = Http::pool(
            fn ($pool) => $allowed->map(
                fn (string $name) => $pool->timeout(5)->get("https://pokeapi.co/api/v2/pokemon/{$name}")
            )->all()
        );

        $results = [];
        $errors = [];

        foreach ($allowed as $i => $name) {
            $resp = $responses[$i];

            if ($resp->successful()) {
                $data = $resp->json();
                $data['source'] = 'pokeapi'; // do custom pokemonow
                $results[] = $data;

                continue;
            }

            $errors[] = [
                'name' => $name,
                'status' => $resp->status(),
            ];
        }

        return response()->json([
            'results' => $results,
            'errors' => $errors,
        ]);
    }
}
