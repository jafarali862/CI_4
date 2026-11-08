<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBooksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title'          => ['type' => 'VARCHAR', 'constraint' => 150],
            'author'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'isbn'           => ['type' => 'VARCHAR', 'constraint' => 13],
            'category'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'published_year' => ['type' => 'SMALLINT', 'constraint' => 4, 'unsigned' => true],
            'published_date' => ['type' => 'DATE'],
            'price'          => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'description'    => ['type' => 'TEXT', 'null' => true],
            'cover_photo'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('books');
    }

    public function down()
    {
        $this->forge->dropTable('books');
    }
}