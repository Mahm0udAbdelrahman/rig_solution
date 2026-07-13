<?php

namespace App\Policies\WorkFlow;

use App\Models\User;
use App\Models\WorkFlow\FileManager;
use Illuminate\Auth\Access\HandlesAuthorization;

class FileManagerPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasPermission('filemanager', 'all');
    }

    public function view(User $user, FileManager $fileManager)
    {
        return $user->hasPermission('filemanager', 'show');
    }

    public function create(User $user)
    {
        return $user->hasPermission('filemanager', 'create');
    }

    public function update(User $user, FileManager $fileManager)
    {
        return $user->hasPermission('filemanager', 'edit');
    }

    public function delete(User $user, FileManager $fileManager)
    {
        return $user->hasPermission('filemanager', 'delete');
    }

    public function deletePhysicalAny(User $user)
    {
        return $user->hasPermission('filemanager', 'physical-delete');
    }

    public function deletePhysical(User $user, FileManager $fileManager)
    {
        return $user->hasPermission('filemanager', 'physical-delete');
    }
}
