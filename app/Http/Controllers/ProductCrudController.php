<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;


class ProductCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = DB::table('products')->paginate(10);

        return Response::json(['products' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $requestErrors = $this->getProductPostRequestErrors($request);
        if ($requestErrors) {
            return $requestErrors;
        }

        $productValues = $request->only(['name', 'description', 'price']);
        $product = Product::factory()->create($productValues);

        return Response::json(new ProductResource($product), HttpResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return Response::json(['error' => 'Product not found'], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json(new ProductResource($product), HttpResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        /** @var Product $product */
        $product = Product::find($id);

        if (!$product) {
            return Response::json(['error' => 'Product not found'], HttpResponse::HTTP_NOT_FOUND);
        }

        $requestErrors = $this->getProductPostRequestErrors($request);
        if ($requestErrors) {
            return $requestErrors;
        }

        $productValues = $request->only(['name', 'description', 'price']);
        $product->update($productValues);

        return Response::json(new ProductResource($product));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = Product::find($id);

        if (!$product) {
            return Response::json(['error' => 'Product not found'], HttpResponse::HTTP_NOT_FOUND);
        }

        $product->delete();

        return Response::json([], HttpResponse::HTTP_OK);
    }

    private function getProductPostRequestErrors(Request $request): ?JsonResponse
    {
        $validateData = [
            'name' => 'required|max:60',
            'description' => 'required',
            'price' => 'required|integer'
        ];
        try {
            $this->validate($request, $validateData);
        } catch (ValidationException $ex) {
            return Response::json(['errors' => $ex->errors()], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return null;
    }
}
