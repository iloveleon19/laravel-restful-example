<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\User;
class AnimalTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testCanCreateAnimal()
    {
        Passport::actingAs(User::first(), ['*']);

        $response = $this->json(
            'POST', 'api/animals',[
            'type_id' => '1',
            'name' => '大黑',
            'birthday' => '2019-10-05',
            'area' => '台北市',
            'fix' => '1'
        ]);

        $response->assertStatus(201)->assertJson([
            'type_id' => '1',
            'name' => '大黑',
            "birthday"=>"2019-10-05",
            "area"=>"台北市",
            "fix"=>"1"
        ]);
    }

    public function testCanNotCreateAnimal()
    {
        $response = $this->json('POST','api/animals',[
            'type_id' => '1',
            'name' => '大黑',
            'birthday' => '2019-10-05',
            'area' => '台北市',
            'fix' => '1',
        ]);

        $response->assertStatus(401)->assertJson([
            'message' => "Unauthenticated."
        ]);
    }

    public function testViewAllAnimal()
    {
        $response = $this->get('api/animals');

        $response->assertJsonStructure([
            'current_page',
            'data' => [
                [
                    'id',
                    'type_id',
                    'name',
                    'birthday',
                    'area',
                    'fix',
                    'description',
                    'personality',
                    'created_at',
                    'updated_at',
                    'user_id'
                ]
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
    }
}
