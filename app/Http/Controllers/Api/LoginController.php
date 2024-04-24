<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Exceptions\HttpException;
use Illuminate\Http\Request;
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
    public function mini(Request $request)
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
        //        dump($result);
        //        [
        //            "session_key" => "nfQZDTK0VEMaBe4IPy3Hvg=="
        //            "openid" => "osQykt6-oHsTRfF2Fr5PzJjRi8Ho"
        //        ]
    }
}
