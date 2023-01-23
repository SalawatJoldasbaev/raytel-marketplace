<?php

use App\Models\Device;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('viewed_products', function (Blueprint $table) {
            $table->id();
            $table->timestamp('viewed_at');
            $table->foreignIdFor(Product::class);
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Device::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('viewed_products');
    }
};
