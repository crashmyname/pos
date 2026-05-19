<?php


use Bpjs\Framework\Helpers\SchemaBuilder;

class CreateCategoriesTable
{
    public function up(\PDO $pdo)
    {
        $table = new SchemaBuilder('categories');
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
        $table->timestamp('updated_at')->default('CURRENT_TIMESTAMP');
        $sql = $table->buildCreateSQL();
        try {
             $pdo->exec($sql);
             echo "Table 'categories' berhasil dibuat\n";
        } catch (\PDOException $e) {
             echo "Gagal membuat tabel: ".$e->getMessage()."\n";
             echo "SQL:".$sql;
        }
    }

    public function down(PDO $pdo)
    {
        $table = new SchemaBuilder('categories');
        $pdo->exec($table->buildDropSQL());
    }
}
