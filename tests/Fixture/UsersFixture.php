<?php

declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'uuid' => ['type' => 'char', 'length' => 32, 'null' => true, 'default' => 'NULL', 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'email' => ['type' => 'string', 'length' => 320, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'name' => ['type' => 'string', 'length' => 32, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'surname' => ['type' => 'string', 'length' => 32, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'username' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'password' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'login_key' => ['type' => 'string', 'length' => 35, 'null' => true, 'default' => 'NULL', 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'admin' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'active_email' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'email' => ['type' => 'unique', 'columns' => ['email'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Shane',
                'surname' => 'Vendrell',
                'email' => 'francesco.bertocchi@gmail.com',
                'active' => '1',
                'active_email' => '1',
                'username' => 'shane',
                'password' => '$2y$10$sIhcPTjv6QCOnI3IYt5RwuXthC2Q.OMjko2L/3J2beDozDwc/6KEK',
                'login_key' => '2e096a8764ef0193f6842895cc98413b',
                'admin' => '1',
            ],
            [
                'id' => 2,
                'name' => 'Stefano',
                'surname' => 'Sonzogni',
                'email' => 'stefano788@gmail.com',
                'active' => '1',
                'active_email' => '1',
                'username' => 'Fiste788',
                'password' => '$2y$10$DTJXpgiG41oi5fjDg.TxFOqV0Wfo0u8XP/LT9Q30ffiqDMBFwHcM.',
                'login_key' => 'be1de040f9f3aa3918be1ced5df95e28',
                'admin' => '1',
            ],
        ];
        parent::init();
    }
}
