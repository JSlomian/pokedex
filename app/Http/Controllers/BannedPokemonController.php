<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BannedPokemon;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class BannedPokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(
            BannedPokemon::pluck('name')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            return response()->json(
                BannedPokemon::create($data),
                Response::HTTP_CREATED
            );
        } catch (QueryException $e) {
            // Można sprawdzić po kodzie czy duplikat
            return response()->json([
                'message' => 'Already banned',
            ], Response::HTTP_CONFLICT);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BannedPokemon $bannedPokemon)
    {
        $bannedPokemon->delete();

        return response()->noContent();
    }
}
