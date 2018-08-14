<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class SmsLogMigration_104
 */
class SmsLogMigration_104 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('sms_log', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 128,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'result',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'error',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'result'
                        ]
                    ),
                    new Column(
                        'sid',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 128,
                            'after' => 'error'
                        ]
                    ),
                    new Column(
                        'phone',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 64,
                            'after' => 'sid'
                        ]
                    ),
                    new Column(
                        'fee',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'size' => 11,
                            'after' => 'phone'
                        ]
                    ),
                    new Column(
                        'ext',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'fee'
                        ]
                    ),
                    new Column(
                        'time',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 32,
                            'after' => 'ext'
                        ]
                    ),
                    new Column(
                        'template_id',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 128,
                            'after' => 'time'
                        ]
                    ),
                    new Column(
                        'params',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'template_id'
                        ]
                    ),
                    new Column(
                        'user_receive_time',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 32,
                            'after' => 'params'
                        ]
                    ),
                    new Column(
                        'nationcode',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 32,
                            'after' => 'user_receive_time'
                        ]
                    ),
                    new Column(
                        'mobile',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 32,
                            'after' => 'nationcode'
                        ]
                    ),
                    new Column(
                        'report_status',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 128,
                            'after' => 'mobile'
                        ]
                    ),
                    new Column(
                        'errmsg',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 32,
                            'after' => 'report_status'
                        ]
                    ),
                    new Column(
                        'description',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'errmsg'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('phone', ['phone'], null),
                    new Index('index_sid', ['sid'], null)
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8mb4_general_ci'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
