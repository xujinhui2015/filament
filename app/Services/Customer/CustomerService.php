<?php

namespace App\Services\Customer;

use App\Enums\Customer\CustomerBalanceSceneTypeEnum;
use App\Models\Customer\Customer;
use App\Support\Exceptions\ResponseException;
use App\Support\Helpers\AccuracyCalcHelper;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Jiannei\Response\Laravel\Support\Facades\Response;

class CustomerService
{
    /**
     * @throws ResponseException
     */
    public static function setBalance(
        Customer                     $customer,
        float|int|string             $money,
        CustomerBalanceSceneTypeEnum $balanceSceneType,
                                     $relationId = null,
        string                       $changeExplain = null,
        string                       $remark = null
    )
    {
        if (AccuracyCalcHelper::begin($money)->isEq()) {
            // 0元不处理
            return;
        }
        if (AccuracyCalcHelper::begin($customer->balance)->isLt($money)) {
            throw new ResponseException('余额不足');
        }

        Db::transaction(function () use ($customer, $money, $balanceSceneType, $relationId, $changeExplain, $remark) {
            $customer->balanceRecords()->create([
                'change_explain' => $changeExplain ?: $balanceSceneType->getLabel(),
                'scene_type' => $balanceSceneType,
                'balance' => $money,
                'relation_id' => $relationId,
                'remark' => $remark,
            ]);
        });
    }
}
