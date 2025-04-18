<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use App\Support\Helpers\FilePathHelper;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('common.util')]
#[Middleware('auth:sanctum')]
class UtilController extends Controller
{

    #[Post('upload')]
    public function upload(Request $request)
    {
        $file = $request->file('file');

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        $extension = $file->getClientOriginalExtension();

        if (!in_array($extension, $allowedExtensions)) {
            return $this->fail('文件类型不允许');
        }

        return $this->success([
            'file_url' => $file->storePublicly(FilePathHelper::uploadDir(FilePathHelper::MINI), 'public')
        ]);

    }

}
