<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Animal;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\AnimalResource;

use App\Http\Requests\StoreAnimalRequest;
use App\Services\AnimalService;

use Auth;

class AnimalController extends Controller
{

    private $animalService;

    public function __construct(AnimalService $animalService)
    {
        $this->animalService = $animalService;
        $this->middleware('auth:api', ['except'=>['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marker = isset($request->marker) ? $request->marker : 1;
        $limit = isset($request->limit) ? $request->limit : 10;

        $query = Animal::query();

        $query = $this->animalService->filterAnimals($request->filters, $query);
        $query = $this->animalService->sortAnimals($request->sort, $query);

        $animals = $query->where('id','>=', $marker)->paginate($limit);

        return response($animals, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreAnimalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnimalRequest $request)
    {
        // $this->validate($request, [
        //     'type_id' => 'required',
        //     'name' => 'required|max:255',
        //     'birthday' => 'required|date',
        //     'area' => 'required|max:255',
        //     'fix' => 'required|boolean',
        //     'description' => 'nullable',
        //     'personality' => 'nullable'
        // ]);

        $animal = Animal::create($request->all());
        return response($animal, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function show(Animal $animal)
    {
        // return response($animal, Response::HTTP_OK);
        return response(new AnimalResource($animal), Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function edit(Animal $animal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Animal $animal)
    {
        $this->authorize('update', $animal);
        $animal->update($request->all());
        return response($animal, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Animal $animal)
    {
        $animal->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function like(Animal $animal)
    {
        $animal->like()->toggle(Auth::user()->id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
