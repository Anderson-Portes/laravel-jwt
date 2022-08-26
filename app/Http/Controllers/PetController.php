<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePetRequest;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json([
            'pets' => $request->user()->pets
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePetRequest $request)
    {
        return response()->json([
            'pet' => Pet::create([
                'name' => $request->name,
                'age' => $request->age,
                'description' => $request->description,
                'photo_path' => $request->photo_path,
                'user_id' => $request->user()->id
            ])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $pet = Pet::where("id", $id)->firstWhere("user_id", $request->user()->id);

        if (!$pet) {
            return response([
                'error' => 'Pet not found!'
            ], 404);
        }

        return response()->json([
            'pet' => $pet
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $pet = Pet::where("id", $id)->firstWhere("user_id", $request->user()->id);

        if (!$pet) {
            return response([
                'error' => 'Pet not found!'
            ], 404);
        }

        $pet->update($request->all());

        return response()->json([
            'pet' => $pet
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $pet = Pet::where("id", $id)->firstWhere("user_id", $request->user()->id);

        if (!$pet) {
            return response([
                'error' => 'Pet not found!'
            ], 404);
        }

        $pet->delete();

        return response()->json([
            'message' => 'Pet deleted successfully!'
        ]);
    }
}
