<?php

namespace App\Controllers;

use App\Models\WorkGroup;
use App\Models\User;

class WorkGroupController
{
    private $workGroupModel;
    private $userModel;

    public function __construct()
    {
        $this->workGroupModel = new WorkGroup();
        $this->userModel = new User();
    }

    public function index()
    {
        $groups = $this->workGroupModel->getGroupsWithUsers();
        include __DIR__ . '/../Views/work-groups/index.php';
    }

    public function assignUser()
    {
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $groupId = filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT);

        if ($userId && $groupId) {
            $this->workGroupModel->assignUserToGroup($userId, $groupId);
        }

        header('Location: /work-groups');
        exit;
    }

    public function removeUser()
    {
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $groupId = filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT);

        if ($userId && $groupId) {
            $this->workGroupModel->removeUserFromGroup($userId, $groupId);
        }

        header('Location: /work-groups');
        exit;
    }

    public function edit($id)
    {
        $name = htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8');

        if (!$name) {
            header('Location: /work-groups');
            exit;
        }

        $this->workGroupModel->update($id, [
            'name' => $name
        ]);

        header('Location: /work-groups');
        exit;
    }

    public function createGroup()
    {
        $name = htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8');

        if (!$name) {
            header('Location: /work-groups');
            exit;
        }

        $this->workGroupModel->create([
            'name' => $name
        ]);

        header('Location: /work-groups');
        exit;
    }

    public function delete($id)
    {
        $this->workGroupModel->delete($id);
        header('Location: /work-groups');
        exit;
    }

    public function showUsers($groupId)
    {
        $group = $this->workGroupModel->getById($groupId);
        if (!$group) {
            header('Location: /work-groups');
            exit;
        }
        
        $users = $this->workGroupModel->getUsersByGroup($groupId);
        $availableUsers = $this->workGroupModel->getAvailableUsers($groupId);
        include __DIR__ . '/../Views/work-groups/users.php';
    }

    public function addUserToGroup($groupId)
    {
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

        if ($userId) {
            $this->workGroupModel->assignUserToGroup($userId, $groupId);
        }

        header('Location: /work-groups/' . $groupId . '/users');
        exit;
    }

    public function removeUserFromGroup($groupId, $userId)
    {
        $this->workGroupModel->removeUserFromGroup($userId, $groupId);
        header('Location: /work-groups/' . $groupId . '/users');
        exit;
    }
}