<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'password'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'phone'       => ['type' => 'VARCHAR', 'constraint' => 15],
            'age'         => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true],
            'gender'      => ['type' => 'VARCHAR', 'constraint' => 10],
            'dob'         => ['type' => 'DATE'],
            'profile_pic' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'address'     => ['type' => 'TEXT'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}