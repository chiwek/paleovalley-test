<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{

    public function test_get_paginated_product_list(): void
    {
        Product::factory()->createMany(50);

        $response = $this->getJson('/api/products');

        $response->assertJsonCount(10, 'products.data');
    }

    public function test_get_single_product_not_found(): void
    {
        Product::factory()->create();

        $response = $this->getJson('/api/products/2');

        $response->assertStatus(404);
    }

    public function test_get_single_product_found(): void
    {
        Product::factory()->create();

        $response = $this->getJson('/api/products/1');

        $response->assertStatus(200);
    }

    public function test_create_product_with_too_long_name_fails(): void
    {
        $productData = [
            'name' => 'verylongnamethatisoverseventycharslonganditdoesnotmatterbecausesixtyisthelimit',
            'description' => 'some description',
            'price' => '100'
        ];

        $response = $this->postJson('api/products', $productData);

        $response->assertStatus(400);
    }

    public function test_create_product_with_missing_description_fails(): void
    {
        $productData = [
            'name' => 'passable name',
            'price' => '100'
        ];

        $response = $this->postJson('api/products', $productData);

        $response->assertStatus(400);
        $response->assertJsonFragment(["The description field is required."]);
    }


    public function test_create_product_with_missing_price_fails(): void
    {
        $productData = [
            'name' => 'passable name',
            'description' => 'some description',
        ];

        $response = $this->postJson('api/products', $productData);

        $response->assertStatus(400);
        $response->assertJsonFragment(["The price field is required."]);
    }

    public function test_create_product_with_non_existing_params_passes(): void
    {
        $productData = [
            'name' => 'passable name',
            'description' => 'some description',
            'price' => '100',
            'non_existing' => 'value'
        ];

        $response = $this->postJson('api/products', $productData);

        $response->assertStatus(201);
        $response->assertJsonMissing(["non_existing"]);
    }

    public function test_update_missing_product_fails(): void
    {
        $productData = [
            'name' => 'passable name',
            'description' => 'some description',
        ];

        $response = $this->putJson('api/products/1', $productData);

        $response->assertStatus(404);
    }

    public function test_update_product_success(): void
    {
        $productData = [
            'name' => 'passable name',
            'description' => 'some description',
            'price' => '100'
        ];
        $product = Product::factory()->create($productData);


        $productUpdateData = [
            'name' => 'new passable name',
            'description' => 'some new description',
            'price' => '1000'
        ];

        $response = $this->putJson('api/products/' . $product->id, $productUpdateData);

        $response->assertStatus(200);

        $response->assertJsonFragment(["new passable name"]);
    }

    public function test_delete_missing_product_fails(): void
    {

        $response = $this->deleteJson('api/products/1');

        $response->assertStatus(404);
    }

    public function test_delete_product_success(): void
    {
        $productData = [
            'name' => 'passable name',
            'description' => 'some description',
            'price' => '100'
        ];
        Product::factory()->create($productData);

        $response = $this->deleteJson('api/products/1');

        $response->assertStatus(200);
    }

}
