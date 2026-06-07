<?php


use Bpjs\Framework\Helpers\SchemaBuilder;

class CreateTransactionsTable
{
    public function up(\PDO $pdo)
    {
        $table = new SchemaBuilder('transactions');
        $table->id();
        $table->bigInteger('user_id');
        $table->string('invoice_number',50);
        $table->dateTime('transaction_date');
        $table->integer('total_item');
        $table->decimal('sub_total','15,2')->default(0);
        $table->decimal('cashback_earn')->default(0);
        $table->decimal('grand_total','15,2');
        $table->decimal('paid_amount','15,2');
        $table->decimal('change_amount','15,2');
        $table->enum('payment_method',['cash','qris','transfer','debit','credit']);
        $table->text('notes');
        $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
        $table->timestamp('updated_at')->default('CURRENT_TIMESTAMP');
        $sql = $table->buildCreateSQL();
        try {
             $pdo->exec($sql);
             echo "Table 'transactions' berhasil dibuat\n";
        } catch (\PDOException $e) {
             echo "Gagal membuat tabel: ".$e->getMessage()."\n";
             echo "SQL:".$sql;
        }
    }

    public function down(PDO $pdo)
    {
        $table = new SchemaBuilder('transactions');
        $pdo->exec($table->buildDropSQL());
    }
}
