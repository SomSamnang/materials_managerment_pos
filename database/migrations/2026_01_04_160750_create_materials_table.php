<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id(); // ល.រ
            $table->string('code')->unique(); // កូដ
            $table->string('name');            // ឈ្មោះ
            $table->integer('stock')->default(0); // ចំនួនស្តុក
            $table->integer('min_stock')->default(0); // ចំនួនស្តុកអប្បបរមា
            $table->decimal('price', 10, 2)->default(0); // តម្លៃឯកតា
            $table->string('image')->nullable(); // រូបភាព
            $table->text('description')->nullable(); // ពិពណ៌នា
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
};
