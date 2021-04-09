<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->double('quantity');
            $table->double('price');
            $table->timestamps();
        });

        DB::unprepared('CREATE TRIGGER TR_cart_AI AFTER INSERT ON cart FOR EACH ROW
        BEGIN 
            UPDATE products SET quantity = quantity - NEW.quantity
            WHERE id = NEW.product_id;
        END');

        DB::unprepared('CREATE TRIGGER TR_cart_AU AFTER UPDATE ON cart FOR EACH ROW
        BEGIN 
            UPDATE products SET quantity = (quantity + OLD.quantity) - NEW.quantity
            WHERE id = NEW.product_id;
        END');

        DB::unprepared('CREATE TRIGGER TR_cart_AD AFTER DELETE ON cart FOR EACH ROW
        BEGIN 
            UPDATE products SET quantity = quantity + OLD.quantity
            WHERE id = OLD.product_id;
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart');
    }
}
