<?php

namespace App\Console\Commands\Tools;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use JetBrains\PhpStorm\NoReturn;

class MakeDocsSidebarCommand extends Command implements Isolatable
{
    protected $signature = 'tool:sidebar';

    protected $description = '生成文档的侧边栏文件的代码';

    #[NoReturn] public function handle(): void
    {
        $docsPath = base_path('docs');
        $dirList = scandir($docsPath);

        $treePathList = [];
        foreach ($dirList as $dir) {
            if ($dir == '.' || $dir == '..') {
                continue;
            }
            $path = $docsPath . DIRECTORY_SEPARATOR . $dir;
            if (is_dir($path)) {
                $sonPathList = scandir($path);
                foreach ($sonPathList as $sonPath) {
                    if ($sonPath == '.' || $sonPath == '..') {
                        continue;
                    }
                    $treePath = $this->parseSingleFile($path . DIRECTORY_SEPARATOR . $sonPath);
                    if ($treePath) {
                        $treePathList[$treePath['dir_name']][] = $treePath;
                    }
                }
            }
        }

        // 渲染文件
        $mdContents = [];
        foreach ($treePathList as $treeName => $treeSons) {

            $mdContents[] = '- ' . $treeName . "\n";

            foreach ($treeSons as $treeSon) {
                $mdContents[] = '    - [' . $treeSon['file_name'] . '](' . $treeSon['origin_dir_name'] . '/' . $treeSon['origin_file_name'] . ')';
            }
        }

        file_put_contents($docsPath . DIRECTORY_SEPARATOR . '_sidebar.md', implode("\n", $mdContents));
    }

    public function parseSingleFile($filePath): ?array
    {
        if (pathinfo($filePath, PATHINFO_EXTENSION) !== 'md') {
            return null;
        }
        $explodePath = explode(DIRECTORY_SEPARATOR, str_replace(base_path('docs') . DIRECTORY_SEPARATOR, '', $filePath));

        $explodeOne = explode('.', $explodePath[0]);

        if (count($explodeOne) == 1) {
            $dirName = $explodeOne[0];
        } else {
            $dirName = $explodeOne[1];
        }

        $explodeTwo = explode('.', $explodePath[1]);

        if (count($explodeTwo) == 2) {
            $fileName = $explodeTwo[0];
        } else {
            $fileName = $explodeTwo[1];
        }

        return [
            'dir_name' => $dirName,
            'origin_dir_name' => $explodePath[0],
            'file_name' => $fileName,
            'origin_file_name' => $explodePath[1],
        ];
    }
}
