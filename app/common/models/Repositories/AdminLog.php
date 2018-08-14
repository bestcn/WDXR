<?php
namespace Wdxr\Models\Repositories;


class AdminLog extends Repositories
{

    /**
     * 添加操作日志
     * @param $name
     * @param $class
     * @param $action
     * @param null $parameters
     * @param null $description
     * @return bool
     */
    public function newAdminLog($name, $class, $action, $parameters = null, $description = null)
    {
        $id = $this->security->getRandom()->uuid();
        $admin_id = $this->session->get('auth-identity')['id'];

        $success = $this->db->insert('admin_log', [
            $name, $description, $class, $action, serialize($parameters), $admin_id, $id
        ], [
            'name', 'description', 'class', 'action', 'parameters', 'admin_id', 'id'
        ]);

        return $success;
    }

}