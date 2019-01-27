<?php

use yii\db\Migration;

/**
 * Class m190127_182120_add_foreign_keys
 */
class m190127_182120_add_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-task_user-user_id',
            'task_user',
            'user_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-task_user-task_id',
            'task_user',
            'task_id',
            'task',
            'id'
        );

        $this->addForeignKey(
            'fk-task-creator_id',
            'task',
            'creator_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-task-updater_id',
            'task',
            'updater_id',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-task_user-user_id',
            'task_user'
        );

        $this->dropForeignKey(
            'fk-task_user-task_id',
            'task_user'
        );

        $this->dropForeignKey(
            'fk-task-creator_id',
            'task'
        );

        $this->dropForeignKey(
            'fk-task-updater_id',
            'task'
        );
    }
}
