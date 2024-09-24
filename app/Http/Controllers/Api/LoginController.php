<?php

namespace App\Http\Controllers\Api;

use App\Enums\TokenEnum;
use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerWechat;
use EasyWeChat\Kernel\Exceptions\HttpException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Overtrue\LaravelWeChat\EasyWeChat;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Prefix('login')]
class LoginController extends Controller
{
    /**
     * 小程序登录
     */
    #[Post('mini')]
    public function mini(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'bail|required',
        ]);

        $app = EasyWeChat::miniApp();
        try {
            $result = $app->getUtils()->codeToSession($request->post('code'));
        } catch (HttpException|ClientExceptionInterface|DecodingExceptionInterface
            |RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface) {
                return $this->fail('登录失败');
        }

        // 返回示例
//        $result = [
//            "session_key" => "nfQZDTK0VEMaBe4IPy3Hvg==",
//            "openid" => "osQykt6-oHsTRfF2Fr5PzJjRi8Ho"
//        ];

        $customerWechat = CustomerWechat::query()
            ->where('mini_openid', $result['openid'])
            ->first();
        if ($customerWechat) {
            $customer = $customerWechat->customer;
        } else {
            $customer = DB::transaction(function () use ($result) {
                $customer = Customer::query()->create();
                $customer->wechat()->create([
                    'mini_openid' => $result['openid'],
                ]);
                return $customer;
            });
        }

        return $this->success([
            'token' => $customer->createToken(TokenEnum::Mini->value)->plainTextToken
        ]);
    }

}
