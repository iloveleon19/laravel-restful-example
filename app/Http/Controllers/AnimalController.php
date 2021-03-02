<?php

namespace App\Http\Controllers;

use App\Animal;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\AnimalResource;

class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marker = $request->marker==null ? 1: $request->marker;
        $limit = $request->limit==null ? 10 : $request->limit;

        $query = Animal::query();

        // 篩選欄位條件
        if(isset($request->filters)){
            $filters = explode(',', $request->filters);
            foreach($filters as $key => $filter){
                list($criteria, $value) = explode(':', $filter);
                $query->where($criteria, 'like', "%$value%");
            }
        }

        // 排序順序
        if(isset($request->sort)){
            $sorts = explode(',', $request->sort);
            foreach($sorts as $key => $sort){
                list($criteria, $value) = explode(':', $sort);
                if($value == 'asc' || $value=='desc') {
                    $query->orderBy($criteria, $value);
                }
            }
        }else{
            $query->orderBy('id','asc');
        }

        $animals = $query->where('id','>=', $marker)->paginate($limit);

        return response($animals, Response::HTTP_OK);

        // $animals = Animal::orderBy('id', 'asc')->where('id', '>=', $marker)->limit($limit)->get();
        $animals = Animal::orderBy('id', 'asc')->where('id', '>=', $marker)->paginate($limit)->get();

        $animals = Animal::get();
        return response(['animals'=>$animals], Response::HTTP_OK);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->validate($request, [
            'type_id' => 'required',
            'name' => 'required|max:255',
            'birthday' => 'required|date',
            'area' => 'required|max:255',
            'fix' => 'required|boolean',
            'description' => 'nullable',
            'personality' => 'nullable'
        ]);

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
}
