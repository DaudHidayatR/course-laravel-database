<?php

namespace Tests\Feature;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class QueryBuilderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        DB::delete('delete from categories');
    }

    public function testInsert(): void
    {
        DB::table('categories')->insert([
            "id" => "GADGET",
            "name" => "Gadget",
            "description" => "Gadget Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
        DB::table('categories')->insert([
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
        $result= DB::select('select count(id) total from categories ');
        self::assertEquals(2, $result[0]->total);
    }
    public function testSelect(){
        $this->testInsert();
        $collection = DB::table('categories')->select(['id','name'])->get();
        self::assertNotNull($collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function insertCategories(){
        DB::table('categories')->insert([
            "id" => "SMARTPHONE",
            "name" => "Smartphone",
            "description" => "Smartphone Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
        DB::table('categories')->insert([
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
        DB::table('categories')->insert([
            "id" => "LAPTOP",
            "name" => "laptop",
            "description" => "laptop Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
        DB::table('categories')->insert([
            "id" => "FASHION",
            "name" => "Fashion",
            "description" => "Fashion Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
    }
    public function testWhere(){
        $this->insertCategories();
        $collection = DB::table('categories')->Where(function (Builder $builder){
            $builder->where('id','=','SMARTPHONE');
            $builder->orWhere('id','=','FOOD');
        })->get();
        self::assertCount(2,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testWhereBetween(){
        $this->insertCategories();
        $collection = DB::table('categories')->whereBetween('created_at',[
            '2020-9-10 10:10:10',
            '2020-11-10 10:10:10'
        ])->get();
        self::assertCount(4,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });

    }
    public function testWhereInMethod(){
        $this->insertCategories();
        $collection = DB::table('categories')->whereIn('id',[
            'SMARTPHONE','FOOD'
        ])->get();
        self::assertCount(2,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testWhereNotNull(){
        $this->insertCategories();
        $collection = DB::table('categories')->
        whereNotNull('description')->get();
        self::assertCount(4,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testWhereDate(){
        $this->insertCategories();
        $collection = DB::table('categories')->
        whereDate('created_at','2020-10-10' )->get();
        self::assertCount(4,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
}
