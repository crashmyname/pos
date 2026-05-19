<?php


use Bpjs\Framework\Helpers\SchemaBuilder;

class CreateTransactionDetailsTable
{
    public function up(\PDO $pdo)
    {
        $table = new SchemaBuilder('transaction_details');
        $table->id();
        $table->bigInteger('transaction_id');
        $table->bigInteger('product_id');
        $table->decimal('qty','15,2');
        $table->decimal('price','15,2');
        $table->decimal('discount_amount','15,2')->default(0);
        $table->decimal('subtotal','15,2');
        $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
        $table->timestamp('updated_at')->default('CURRENT_TIMESTAMP');
        $sql = $table->buildCreateSQL();
        try {
             $pdo->exec($sql);
             echo "Table 'transaction_details' berhasil dibuat\n";
        } catch (\PDOException $e) {
             echo "Gagal membuat tabel: ".$e->getMessage()."\n";
             echo "SQL:".$sql;
        }
    }

    public function down(PDO $pdo)
    {
        $table = new SchemaBuilder('transaction_details');
        $pdo->exec($table->buildDropSQL());
    }
}
