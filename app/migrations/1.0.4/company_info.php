<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class CompanyInfoMigration_104
 */
class CompanyInfoMigration_104 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('company_info', [
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
                        'licence',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'licence_num',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 128,
                            'after' => 'licence'
                        ]
                    ),
                    new Column(
                        'account_permit',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'licence_num'
                        ]
                    ),
                    new Column(
                        'credit_code',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'account_permit'
                        ]
                    ),
                    new Column(
                        'legal_name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 128,
                            'after' => 'credit_code'
                        ]
                    ),
                    new Column(
                        'scope',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 1024,
                            'after' => 'legal_name'
                        ]
                    ),
                    new Column(
                        'period',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 128,
                            'after' => 'scope'
                        ]
                    ),
                    new Column(
                        'idcard_up',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'period'
                        ]
                    ),
                    new Column(
                        'idcard_down',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'idcard_up'
                        ]
                    ),
                    new Column(
                        'photo',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'idcard_down'
                        ]
                    ),
                    new Column(
                        'contacts',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'photo'
                        ]
                    ),
                    new Column(
                        'contact_title',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'contacts'
                        ]
                    ),
                    new Column(
                        'contact_phone',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'contact_title'
                        ]
                    ),
                    new Column(
                        'type',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 4,
                            'after' => 'contact_phone'
                        ]
                    ),
                    new Column(
                        'intro',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'type'
                        ]
                    ),
                    new Column(
                        'address',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'intro'
                        ]
                    ),
                    new Column(
                        'province',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "0",
                            'size' => 32,
                            'after' => 'address'
                        ]
                    ),
                    new Column(
                        'city',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "0",
                            'size' => 32,
                            'after' => 'province'
                        ]
                    ),
                    new Column(
                        'district',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "0",
                            'size' => 32,
                            'after' => 'city'
                        ]
                    ),
                    new Column(
                        'zipcode',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 128,
                            'after' => 'district'
                        ]
                    ),
                    new Column(
                        'createAt',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "CURRENT_TIMESTAMP",
                            'size' => 1,
                            'after' => 'zipcode'
                        ]
                    ),
                    new Column(
                        'url',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'createAt'
                        ]
                    ),
                    new Column(
                        'idcard',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'url'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1339',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
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
