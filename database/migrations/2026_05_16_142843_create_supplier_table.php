<?php


use Bpjs\Framework\Helpers\SchemaBuilder;

class CreateSupplierTable
{
    public function up(\PDO $pdo)
    {
        $table = new SchemaBuilder('suppliers');
        $table->id();
        $table->string('name');
        $table->string('phone',13)->nullable();
        $table->string('email')->nullable();
        $table->text('description')->nullable();
        $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
        $table->timestamp('updated_at')->default('CURRENT_TIMESTAMP');
        $sql = $table->buildCreateSQL();
        try {
             $pdo->exec($sql);
             echo "Table 'supplier' berhasil dibuat\n";
        } catch (\PDOException $e) {
             echo "Gagal membuat tabel: ".$e->getMessage()."\n";
             echo "SQL:".$sql;
        }
    }

    public function down(PDO $pdo)
    {
        $table = new SchemaBuilder('suppliers');
        $pdo->exec($table->buildDropSQL());
    }
}
