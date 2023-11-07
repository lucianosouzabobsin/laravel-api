<?php

namespace Tests\Utils;

use Illuminate\Database\Capsule\Manager;

class TestSetup
{
    protected $db;

    public function setUp()
    {
        $this->initDb();
    }

    protected function initDb()
    {
        $capsule = new Manager();
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        $this->db = $capsule->getDatabaseManager();

        $resourcesDirectory = dirname(__DIR__). '/Resources';

        $importSql = file_get_contents($resourcesDirectory. '/database/tables/dump_users.sql');
        $this->db->statement($importSql);
        $importSql = file_get_contents($resourcesDirectory. '/database/tables/dump_personal_access_tokens.sql');
        $this->db->statement($importSql);
        $importSql = file_get_contents($resourcesDirectory. '/database/tables/dump_modules.sql');
        $this->db->statement($importSql);
        $importSql = file_get_contents($resourcesDirectory. '/database/tables/dump_users_groups.sql');
        $this->db->statement($importSql);
        $importSql = file_get_contents($resourcesDirectory. '/database/tables/dump_modules_actions.sql');
        $this->db->statement($importSql);
        $importSql = file_get_contents($resourcesDirectory. '/database/tables/dump_abilities.sql');
        $this->db->statement($importSql);


        $importSql = file_get_contents($resourcesDirectory. '/database/data/inject_users.sql');
        $this->db->statement($importSql);
        $importSql = file_get_contents($resourcesDirectory. '/database/data/inject_modules.sql');
        $this->db->statement($importSql);
        $importSql = file_get_contents($resourcesDirectory. '/database/data/inject_users_groups.sql');
        $this->db->statement($importSql);
        $importSql = file_get_contents($resourcesDirectory. '/database/data/inject_modules_actions.sql');
        $this->db->statement($importSql);
        $importSql = file_get_contents($resourcesDirectory. '/database/data/inject_abilities.sql');
        $this->db->statement($importSql);
    }
}
