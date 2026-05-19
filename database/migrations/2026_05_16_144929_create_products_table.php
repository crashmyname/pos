<?php


use Bpjs\Framework\Helpers\SchemaBuilder;

class CreateProductsTable
{
    public function up(\PDO $pdo)
    {
        $table = new SchemaBuilder('products');
        $table->id();
        $table->bigInteger('category_id')->notNullable();
        $table->bigInteger('supplier_id')->notNullable();
        $table->string('qrcode')->notNullable();
        $table->string('name')->notNullable();
        $table->text('description')->nullable();
        $table->decimal('buy_price','15,2');
        $table->decimal('sell_price','15,2');
        $table->bigInteger('stock_id')->nullable();
        $table->string('image')->nullable();
        $table->integer('is_acative')->default(1);
        $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
        $table->timestamp('updated_at')->default('CURRENT_TIMESTAMP');
        $sql = $table->buildCreateSQL();
        try {
             $pdo->exec($sql);
             echo "Table 'products' berhasil dibuat\n";
        } catch (\PDOException $e) {
             echo "Gagal membuat tabel: ".$e->getMessage()."\n";
             echo "SQL:".$sql;
        }
    }

    public function down(PDO $pdo)
    {
        $table = new SchemaBuilder('products');
        $pdo->exec($table->buildDropSQL());
    }
}
