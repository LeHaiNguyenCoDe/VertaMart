<?php

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
        Schema::table('orders', function (Blueprint $table) {
            // Thêm cột customer_id
            $table->unsignedBigInteger('customer_id');

            // Thêm khóa ngoại liên kết với bảng customers
            $table->foreign('customer_id')->references('id')->on('customers')
                ->onDelete('cascade')  // Khi khách hàng bị xóa, các đơn hàng liên quan cũng sẽ bị xóa
                ->onUpdate('cascade'); // Cập nhật customer_id nếu customer bị cập nhật
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Xóa khóa ngoại và cột customer_id
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};
