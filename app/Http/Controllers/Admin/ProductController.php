<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $products = Product::all();

        return sendResponse(
            'Products retrieved successfully',
            $products,
            Response::HTTP_OK
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ProductStoreRequest $productStoreRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductStoreRequest $productStoreRequest)
    {
        $product = new Product();
        $product->name = $productStoreRequest->get('name');
        $product->price = $productStoreRequest->get('price');
        $product->slug = createSlug($productStoreRequest->get('name'));

        if ( $productStoreRequest->hasFile('image') ){
            $product->image = uploadImage($productStoreRequest->file('image'), 'products');
        }

        if ( $product->save() ){
            return sendResponse(
                'Product created successfully',
                $product,
                Response::HTTP_CREATED
            );
        }

        return sendError(
            'Product could not be created',
            ['error' => 'Internal server error'],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::find($id);

        if ( ! $product) {
            return sendResponse(
                'Product not found',
                null,
                Response::HTTP_NOT_FOUND
            );
        }

        return sendResponse(
            'Product retrieved successfully',
            $product,
            Response::HTTP_OK
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ProductUpdateRequest $productUpdateRequest
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductUpdateRequest $productUpdateRequest, int $id)
    {
        $product = Product::find($id);

        if ( ! $product) {
            return sendResponse(
                'Product not found',
                null,
                Response::HTTP_NOT_FOUND
            );
        }

        if ( $productUpdateRequest->has('name') )
            $product->name = $productUpdateRequest->get('name');
        if ( $productUpdateRequest->has('price') )
            $product->price = $productUpdateRequest->get('price');

        if ( $productUpdateRequest->hasFile('image') ){
            $product->image = uploadImage($productUpdateRequest->file('image'), 'products');
        }

        if ( $product->save() ){
            return sendResponse(
                'Product updated successfully',
                $product,
                Response::HTTP_OK
            );
        }

        return sendError(
            'Product could not be updated',
            ['error' => 'Internal server error'],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $product = Product::find($id);

        if ( ! $product) {
            return sendResponse(
                'Product not found',
                null,
                Response::HTTP_NOT_FOUND
            );
        }

        $product->delete();

        return sendResponse(
            'Product deleted successfully',
            null,
            Response::HTTP_OK
        );
    }


    public function search()
    {
        $query = request()->query('query');

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->get();

        return sendResponse(
            'Products retrieved successfully',
            $products,
            Response::HTTP_OK
        );
    }
}
