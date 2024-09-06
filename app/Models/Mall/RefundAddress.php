<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 * @method static Builder|RefundAddress query()
 */
class RefundAddress extends BaseModel
{
    use SoftDeletes;

    const null UPDATED_AT = null;

    protected $table = 'refund_address';

}
