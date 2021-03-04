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
     * Show Animal List
     * 
     * @queryParam maker 標記由哪個資源開始查詢(預設ID:1) Example: 1
     * @queryParam limit 設定回傳資料筆數(預設10筆資料) Example: 10
     * @queryParam sort 設定排序方式 Example: name:asc
     * @queryParam filter 設定排序方式 Example: name:john
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 預設值
        $marker = isset($request->marker) ? $request->marker : 1;
        $limit = isset($request->limit) ? $request->limit : 10;

        $query = Animal::query();

        $query = $this->animalService->filterAnimals($request->filters, $query);
        $query = $this->animalService->sortAnimals($request->sort, $query);

        $animals = $query->where('id','>=', $marker)->paginate($limit);

        return response($animals, Response::HTTP_OK);
    }

    /**
     * Store Animal
     * 
     * @authenticated
     * 
     * @bodyParam type_id Int required 動物的分類ID(需參照types資料表) Example: 1
     * @bodyParam name String required 動物名稱 Example: 黑熊
     * @bodyParam birthday date required 生日 Example: 2019-10-10
     * @bodyParam area String required 所在區域 Example: 台北市
     * @bodyParam fix boolean required 是否結紮 Example: true
     * @bodyParam description text 簡易描述 Example: 黑狗，胸前有白毛！宛如台灣黑 
     * @bodyParam personality text 其他介紹 Example: 非常親人！很可愛～
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
     * Show Animal
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
     * Update Animal
     * 
     * @bodyParam type_id Int required 動物的分類ID(需參照types資料表) Example: 1
     * @bodyParam name String required 動物名稱 Example: 黑熊
     * @bodyParam birthday date required 生日 Example: 2019-10-10
     * @bodyParam area String required 所在區域 Example: 台北市
     * @bodyParam fix boolean required 是否結紮 Example: true
     * @bodyParam description text 簡易描述 Example: 黑狗，胸前有白毛！宛如台灣黑熊
     * @bodyParam personality text 其他介紹 Example: 非常親人！很可愛～
     * 
     * @authenticated
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
     * Delete Animal
     * 
     * @authenticated
     * 
     * @param  \App\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Animal $animal)
    {
        $animal->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Like/Unlike Animal
     *
     * @authenticated
     * 
     * @param  \App\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function like(Animal $animal)
    {
        $animal->like()->toggle(Auth::user()->id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
