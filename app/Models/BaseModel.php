<?php
namespace App\Models;

use App\Support\Traits\FormatModelDateTrait;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use FormatModelDateTrait;

    /**
     * 基础分页方法, 超过100条数据返回100条
     */
    public function getPerPage(): int
    {
        return min(request()->input('per_page', $this->perPage), 100);
    }
}
