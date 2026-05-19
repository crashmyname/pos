<?php


use Bpjs\Framework\Helpers\SchemaBuilder;

class CreateCashbackHistoriesTable
{
    public function up(\PDO $pdo)
    {
        $table = new SchemaBuilder('cashback_histories');
        $table->id();
        $table->string('member',10);
        $table->bigInteger('transaction_id');
        $table->enum('type',['earn','use','expired','adjust']);
        $table->decimal('amount','15,2');
        $table->decimal('balance_before','15,2');
        $table->decimal('balance_after','15,2');
        $table->text('description');
        $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
        $table->timestamp('updated_at')->default('CURRENT_TIMESTAMP');
        $sql = $table->buildCreateSQL();
        try {
             $pdo->exec($sql);
             echo "Table 'cashback_histories' berhasil dibuat\n";
        } catch (\PDOException $e) {
             echo "Gagal membuat tabel: ".$e->getMessage()."\n";
             echo "SQL:".$sql;
        }
    }

    public function down(PDO $pdo)
    {
        $table = new SchemaBuilder('cashback_histories');
        $pdo->exec($table->buildDropSQL());
    }
}
