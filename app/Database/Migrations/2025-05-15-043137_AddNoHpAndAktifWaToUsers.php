<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNoHpAndAktifWaToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => false,
                'after'      => 'email' // atau sesuaikan dengan kolom terakhir sebelumnya
            ],
            'aktif_wa' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Tidak'],
                'default'    => 'Tidak',
                'null'       => false,
                'after'      => 'no_hp'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['no_hp', 'aktif_wa']);
    }
}
