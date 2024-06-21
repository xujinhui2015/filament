<?php

namespace App\Console\Commands\Tools;

use Doctrine\DBAL\Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use SplFileObject;

class ModelPropertyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'property';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '设置模型注释';

    /**
     * 需要排除生成的文件名称
     */
    protected array $excludeFile = [
        'BaseModel.php',
    ];

    /**
     * 字段映射
     */
    protected array $columnMapping = [
        'bigint' => 'int',
        'tinyint' => 'int',
        'mediumint' => 'int',
        'smallint' => 'int',
        'integer' => 'int',
        'int' => 'int',
        'varchar' => 'string',
        'char' => 'string',
        'timestamp' => 'Carbon',
        'datetime' => 'Carbon',
        'date' => 'string',
        'decimal' => 'double',
        'longtext' => 'string',
        'text' => 'string',
        'string' => 'string',
        'boolean' => 'bool',
        'json' => 'string',
    ];

    /**
     * 需要追加的命名空间 $value 表示是否不经过属性使用判断直接追加命名, null表示存在多属性才添加
     */
    protected array $appendNamespaceList = [
        'Illuminate\Database\Eloquent\Builder' => true,
        'Illuminate\Support\Carbon' => false,
        'Illuminate\Support\Collection' => null,
    ];

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $appModelPath = app_path('Models');
        $dirList = scandir($appModelPath);
        foreach ($dirList as $dir) {
            if ($dir == '.' || $dir == '..') {
                continue;
            }
            $path = $appModelPath.'/'.$dir;
            if (is_dir($path)) {
                foreach (scandir($path) as $value) {
                    if ($value == '.' || $value == '..') {
                        continue;
                    }
                    $filePath = $path.'/'.$value;
                    if (is_file($filePath)) {
                        $this->parseSingleFile($filePath);
                    }
                }
            } else {
                $this->parseSingleFile($path);
            }
        }
    }

    /**
     * @throws Exception
     */
    protected function parseSingleFile($filePath): void
    {
        if (in_array(basename($filePath), $this->excludeFile)) {
            return;
        }
        $fileContent = file_get_contents($filePath);

        preg_match('/namespace (.*?);/', $fileContent, $spaceMatch);
        $spaceName = $spaceMatch[1];

        preg_match('/class (.*?) extends/', $fileContent, $classMatch);

        $className = $classMatch[1];
        $class = $spaceName.'\\'.$className;

        if (! class_exists($class)) {
            return;
        }

        $instance = new $class();
        if (! ($instance instanceof Model)) {
            return;
        }

        // 扫描表的属性
        $columnList = Schema::getConnection()->getSchemaBuilder()->getColumns($instance->getTable());

        // 记录所有使用的类型
        $columnTypes = [];

        foreach ($columnList as $column) {
            $columnComment = $column['comment'];
            if ($columnComment) {
                $columnComment = ' '.$column['comment'];
            }
            $columnType = $this->columnMapping[$column['type_name']];
            $columnTypes[] = $columnType;
            if (! $column['nullable']) {
                $columnType .= '|null';
            }
            $comments[$column['name']] = ' * @property '.$columnType.' $'.$column['name'].$columnComment;
        }

        // 扫描表的关联
        $classReflect = new ReflectionClass($instance);
        $methods = $classReflect->getMethods(ReflectionMethod::IS_PUBLIC);
        $isTotalMany = false;
        foreach ($methods as $method) {
            if ($method->isAbstract() || $method->isStatic()) {
                continue;
            }
            $methodName = $method->getName();
            if (! ($fileContent && strpos($fileContent, 'function '.$methodName))) {
                continue;
            }
            if (preg_match('/get(.*?)Attribute/', $methodName, $match)) {
                $var = Str::snake($match[1]);
                if (isset($comments[$var])) {
                    unset($comments[$var]);
                }
                $comments[$var] = ' * @property $'.$var;
            } else {
                // 模型关联渲染
                $startLine = $method->getStartLine();
                $endLine = $method->getEndLine();
                $methodContent = $this->readFile($filePath, $startLine, $endLine);
                // 去掉代码中的注释
                $pattern = '/\/\*.*?\*\/|\/\/.*?$/ms';
                $methodContent = preg_replace($pattern, '', $methodContent);
                // 判断是否是关联方法
                $allRelation = [
                    '$this->hasOne',
                    '$this->belongsTo',
                    '$this->hasMany',
                    '$this->belongsToMany',
                    '$this->morphTo',
                    '$this->morphMany',
                    '$this->morphToMany',
                    '$this->hasManyThrough',
                ];
                $isRelation = false;
                $thisRelation = null;
                foreach ($allRelation as $relation) {
                    if (strpos($methodContent, $relation)) {
                        $isRelation = true;
                        $thisRelation = $relation;
                        break;
                    }
                }
                if (! $isRelation) {
                    continue;
                }
                preg_match('/return.*?->(.*?)\(,?(.*?)::class/', $methodContent, $match);
                if (empty($match)) {
                    continue;
                }
                // 是否是对多关联
                $isMany = in_array($thisRelation, [
                    '$this->hasMany',
                    '$this->morphMany',
                    '$this->morphToMany',
                    '$this->hasManyThrough',
                ]);
                $docTitle = $this->getDocTitle($method->getDocComment());
                if ($isMany) {
                    $isTotalMany = true;
                }
                $comments[] = ' * @property '.($isMany ? 'Collection|' : '').$match[2].($isMany ? '[]' : '').' $'.$methodName.($docTitle ? ' '.$docTitle : '');
            }
        }

        // 额外追加条目
        $comments[] = ' *';
        $comments[] = ' * @method static Builder|'.$className.' query()';

        // 获取代码中所有的Use语句
        $tokens = token_get_all($fileContent);
        $useStatements = [];
        foreach ($tokens as $key => $token) {
            if ($token[0] == T_USE) {
                $useStatement = '';
                $nextToken = $tokens[$key + 1];
                while ($nextToken[0] != ';') {
                    $useStatement .= is_array($nextToken) ? $nextToken[1] : $nextToken;
                    $nextToken = $tokens[++$key + 1];
                }
                $useStatements[] = trim($useStatement);
            }
        }
        // 判断是否已设置use
        foreach ($this->appendNamespaceList as $appendNamespace => $isAttribute) {
            if (in_array($appendNamespace, $useStatements)) {
                continue;
            }
            // 判断是否是要设置use
            if ($isAttribute === false) {
                $useClass = explode('\\', $appendNamespace);
                if (! in_array(end($useClass), $columnTypes)) {
                    continue;
                }
            } elseif ($isAttribute === null) {
                // 存在多属性才添加
                if (!$isTotalMany) {
                    continue;
                }
            }
            $useAppendNamespaceCodes[] = 'use '.$appendNamespace.';';
        }

        $classComments = "\n\n/**\n".implode("\n", array_values($comments))."\n */\n";

        // 添加use
        if (isset($useAppendNamespaceCodes)) {
            $classComments = "\n".implode("\n", $useAppendNamespaceCodes).$classComments;
        }
        $fileContent = preg_replace('/^([\s\S]*;)([\s\S]*?)(class.*?extends[\s\S]*)$/', "$1$classComments$3", $fileContent);

        file_put_contents($filePath, $fileContent);
    }

    /**
     * 读取文件的指定内容
     */
    protected function readFile($file_name, $start, $end): string
    {
        $limit = $end - $start;
        $f = new SplFileObject($file_name, 'r');
        $f->seek($start);
        $ret = '';
        for ($i = 0; $i < $limit; $i++) {
            $ret .= $f->current();
            $f->next();
        }

        return $ret;
    }

    /**
     * 获取类或者方法注释的标题，第一行
     */
    protected function getDocTitle($docComment): string
    {
        if ($docComment !== false) {
            $docCommentArr = explode("\n", $docComment);
            $comment = trim($docCommentArr[1]);

            return trim(substr($comment, strpos($comment, '*') + 1));
        }

        return '';
    }
}
