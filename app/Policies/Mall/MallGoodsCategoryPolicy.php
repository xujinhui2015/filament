<?php

namespace App\Policies\Mall;

use App\Models\User;
use App\Models\Mall\MallGoodsCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class MallGoodsCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_mall::mall::goods::category');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MallGoodsCategory $mallGoodsCategory): bool
    {
        return $user->can('view_mall::mall::goods::category');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_mall::mall::goods::category');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MallGoodsCategory $mallGoodsCategory): bool
    {
        return $user->can('update_mall::mall::goods::category');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MallGoodsCategory $mallGoodsCategory): bool
    {
        return $user->can('delete_mall::mall::goods::category');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_mall::mall::goods::category');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, MallGoodsCategory $mallGoodsCategory): bool
    {
        return $user->can('force_delete_mall::mall::goods::category');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_mall::mall::goods::category');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, MallGoodsCategory $mallGoodsCategory): bool
    {
        return $user->can('restore_mall::mall::goods::category');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_mall::mall::goods::category');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, MallGoodsCategory $mallGoodsCategory): bool
    {
        return $user->can('replicate_mall::mall::goods::category');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_mall::mall::goods::category');
    }
}
