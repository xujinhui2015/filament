<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\IdsRequest;
use App\Http\Requests\Mall\MallAddressStoreRequest;
use App\Models\Customer\CustomerAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('customer.address')]
#[Middleware('auth:sanctum')]
class AddressController extends Controller
{
    #[Post('index')]
    public function index(): JsonResponse
    {
        return $this->success(CustomerAddress::query()
            ->where('customer_id', $this->getCustomerId())
            ->select([
                'id', 'name', 'phone', 'province', 'city', 'district', 'address', 'updated_at', 'created_at',
            ])
            ->get());
    }

    #[Post('store')]
    public function store(MallAddressStoreRequest $request): JsonResponse
    {
        $storeData = $request->safe([
            'name', 'phone', 'province', 'city', 'district', 'address',
        ]);

        if ($request->has('id')) {
            CustomerAddress::query()
                ->whereId($request->post('id'))
                ->where('customer_id', $this->getCustomerId())
                ->update($storeData);
        } else {
            CustomerAddress::query()
                ->create([
                    'customer_id' => $this->getCustomer()->id,
                    ...$storeData,
                ]);
        }

        return $this->ok();
    }

    #[Post('destroy')]
    public function destroy(IdsRequest $request): JsonResponse
    {
        CustomerAddress::query()
            ->where('customer_id', $this->getCustomerId())
            ->find($request->post('id'))
            ->delete();

        return $this->ok();
    }

}
