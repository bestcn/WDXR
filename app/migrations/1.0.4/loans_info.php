<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class LoansInfoMigration_104
 */
class LoansInfoMigration_104 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('loans_info', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'step',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 3,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'u_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'size' => 20,
                            'after' => 'step'
                        ]
                    ),
                    new Column(
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'u_id'
                        ]
                    ),
                    new Column(
                        'state',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 3,
                            'after' => 'name'
                        ]
                    ),
                    new Column(
                        'time',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'state'
                        ]
                    ),
                    new Column(
                        'sex',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'size' => 3,
                            'after' => 'time'
                        ]
                    ),
                    new Column(
                        'identity',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 100,
                            'after' => 'sex'
                        ]
                    ),
                    new Column(
                        'license',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'identity'
                        ]
                    ),
                    new Column(
                        'household',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 20,
                            'after' => 'license'
                        ]
                    ),
                    new Column(
                        'province',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 6,
                            'after' => 'household'
                        ]
                    ),
                    new Column(
                        'city',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 6,
                            'after' => 'province'
                        ]
                    ),
                    new Column(
                        'area',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 6,
                            'after' => 'city'
                        ]
                    ),
                    new Column(
                        'address',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'area'
                        ]
                    ),
                    new Column(
                        'business',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'address'
                        ]
                    ),
                    new Column(
                        'money',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 5,
                            'after' => 'business'
                        ]
                    ),
                    new Column(
                        'term',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 3,
                            'after' => 'money'
                        ]
                    ),
                    new Column(
                        'purpose',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'term'
                        ]
                    ),
                    new Column(
                        'device_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'purpose'
                        ]
                    ),
                    new Column(
                        'partner_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'size' => 20,
                            'after' => 'device_id'
                        ]
                    ),
                    new Column(
                        'user_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'size' => 20,
                            'after' => 'partner_id'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '672',
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
