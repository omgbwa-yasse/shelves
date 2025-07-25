<?php

namespace App\Policies;

use App\Models\Shelf;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Policies\BasePolicy;

class ShelfPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool|Response
    {
        return $this->canViewAny($user, 'shelf_viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Shelf $shelf): bool|Response
    {
        return $this->canView($user, $shelf, 'shelf_view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool|Response
    {
        return $this->canCreate($user, 'shelf_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Shelf $shelf): bool|Response
    {
        return $this->canUpdate($user, $shelf, 'shelf_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Shelf $shelf): bool|Response
    {
        return $this->canDelete($user, $shelf, 'shelf_delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(?User $user, Shelf $shelf): bool|Response
    {
        return $this->canForceDelete($user, $shelf, 'shelf_force_delete');
    }";

        return Cache::remember($cacheKey, now()->addMinutes(10), function() use ($user, $shelf) {
            // For models directly linked to organisations
            if (method_exists($shelf, 'organisations')) {
                foreach($shelf->organisations as $organisation) {
                    if ($organisation->id == $user->current_organisation_id) {
                        return true;
                    }
                }
            }

            // For models with organisation_id column
            if (isset($shelf->organisation_id)) {
                return $shelf->organisation_id == $user->current_organisation_id;
            }

            // For models linked through activity (like Record)
            if (method_exists($shelf, 'activity') && $shelf->activity) {
                foreach($shelf->activity->organisations as $organisation) {
                    if ($organisation->id == $user->current_organisation_id) {
                        return true;
                    }
                }
            }

            // Default: allow access if no specific organisation restriction
            return true;
        });
    }
}
