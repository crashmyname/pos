<?php


use Bpjs\Framework\Helpers\SchemaBuilder;

class CreateSetupTransactionTable
{
    public function up(\PDO $pdo)
    {
        $table = new SchemaBuilder('setup_transaction');
        $table->id();
        $table->date('closing_date')->nullable();
        $table->enum('status',['1','0'])->default('0');
        $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
        $table->timestamp('updated_at')->default('CURRENT_TIMESTAMP');
        $sql = $table->buildCreateSQL();
        try {
             $pdo->exec($sql);
             echo "Table 'setup_transaction' berhasil dibuat\n";
        } catch (\PDOException $e) {
             echo "Gagal membuat tabel: ".$e->getMessage()."\n";
             echo "SQL:".$sql;
        }
    }

    public function down(PDO $pdo)
    {
        $table = new SchemaBuilder('setup_transaction');
        $pdo->exec($table->buildDropSQL());
    }
}
