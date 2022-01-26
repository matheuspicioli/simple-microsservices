<?php

namespace App\Http\Controllers;

use App\Jobs\ProductLiked;
use App\Models\Product;
use App\Models\ProductUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function like($id, Request $request)
    {
        // command to found a host ip
        // /sbin/ip route|awk '/default/ { print $3 }'
        $response = Http::timeout(5)->get('http://172.18.0.1:8000/api/user');
        $user = $response->json();

        try {
            $productUser = ProductUser::create([
                'user_id' => $user['id'],
                'product_id' => $id,
            ]);

            ProductLiked::dispatch($productUser->toArray())->onQueue('admin_queue');

            return response([
                'message' => 'success'
            ]);
        } catch (\Exception $ex) {
            return response([
                'message' => 'You already liked this product'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
