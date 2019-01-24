<?php
namespace app\commands;

use app\models\User;
use yii\console\Controller;
use Yii;
use yii\db\SchemaBuilderTrait;
use yii\helpers\Console;

/**
 * This command create
 * - Users table
 * - Main roles and permissions
 *
 */
class RbacController extends Controller
{
    use SchemaBuilderTrait;

    protected function getDb()
    {
        return Yii::$app->getDb();
    }


    public function createUserTable()
    {
        $this->stdout('Create User table with admin...');

        $command = $this->getDb()->createCommand();
        $command->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'authKey' => $this->string(),
            'accessToken' => $this->string(),
            'account' => $this->decimal(10,2)->defaultValue(0),
            'reputation' => $this->integer()->defaultValue(0),
        ])->execute();

        $admin = new User();
        $admin->username = 'admin';
        $admin->password = '1qaz2wsx';
        $admin->save();

        $this->stdout('Done!'. PHP_EOL, Console::FG_GREEN);
    }

    public function actionInit()
    {
        $this->stdout('Start init RBAC...' . PHP_EOL);
        $tables = Yii::$app->db->schema->tableNames;
        if (!in_array('users', $tables)) {
            $this->createUserTable();
        }

        $this->stdout('Create permissions...');
        $auth = Yii::$app->authManager;
        $permissions = [
            [
                'name' => 'buy_item',
                'desc' => 'Buy a item'
            ],
            [
                'name' => 'profile_edit_own',
                'desc' => 'Edit own profile'
            ],
            [
                'name' => 'profile_edit_foreign',
                'desc' => 'Edit foreign profile'
            ],
            [
                'name' => 'account_payment',
                'desc' => 'Deposit to account'
            ],
            [
                'name' => 'change_rep_foreign',
                'desc' => 'Change foreign reputation',
            ],
            [
                'name' => 'create_item',
                'desc' => 'Create item'
            ],
            [
                'name' => 'edit_item',
                'desc' => 'Edit item'
            ],
            [
                'name' => 'edit_item_foreign',
                'desc' => 'Edit foreign item'
            ],
            [
                'name' => 'drop_item',
                'desc' => 'Drop item',
            ],
            [
                'name' => 'drop_item_foreign',
                'desc' => 'Drop foreign item',
            ],
        ];

        $children = [];
        foreach ($permissions as $permission) {
            $item = $auth->createPermission($permission['name']);
            $item->description = $permission['desc'];
            $auth->add($item);
            $children[$permission['name']] = $item;
        }
        $this->stdout('Done!' . PHP_EOL, Console::FG_GREEN);

        $this->stdout('Create Client role...');
        $client = $auth->createRole('client');
        $auth->add($client);
        $auth->addChild($client, $children['buy_item']);
        $auth->addChild($client, $children['profile_edit_own']);
        $auth->addChild($client, $children['account_payment']);
        $auth->addChild($client, $children['change_rep_foreign']);
        $this->stdout('Done!' . PHP_EOL, Console::FG_GREEN);

        $this->stdout('Create Seller role...');
        $seller = $auth->createRole('seller');
        $auth->add($seller);
        $auth->addChild($seller, $client);
        $auth->addChild($seller, $children['create_item']);
        $auth->addChild($seller, $children['edit_item']);
        $auth->addChild($seller, $children['drop_item']);
        $this->stdout('Done!' . PHP_EOL, Console::FG_GREEN);

        $this->stdout('Create Admin role...');
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $children['profile_edit_foreign']);
        $auth->addChild($admin, $children['edit_item_foreign']);
        $auth->addChild($admin, $children['drop_item_foreign']);
        $this->stdout('Done!' . PHP_EOL, Console::FG_GREEN);

        $auth->assign($admin, 1);

    }
}
