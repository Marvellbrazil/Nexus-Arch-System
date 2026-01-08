<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

use function PHPSTORM_META\type;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'role_id' => [ // FK
                'type' => 'INT',
                'unsigned' => true,
            ],
            'department_id' => [ // FK
                'type' => 'INT',
                'unsigned' => true,
            ],
            'phone_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'photo_profile' => [
                'type' => 'TEXT',
                'default' => null,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'user_otp' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
                'null' => true,
                'default' => null,
            ],
            'last_login_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => 'null',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => 'null',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => 'null',
            ],
        ]);

        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('role_id', 'roles', 'role_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('department_id', 'departments', 'department_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
