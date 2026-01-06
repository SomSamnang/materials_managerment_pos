<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcceptedStatusToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the 'accepted' status is already in the enum
        $result = \DB::select("SHOW COLUMNS FROM invoices WHERE Field = 'status'");
        if ($result) {
            $type = $result[0]->Type;
            if (strpos($type, "'accepted'") === false) {
                \DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('unpaid', 'paid', 'overdue', 'accepted') DEFAULT 'unpaid'");
            }
        }
    }

    public function down()
    {
        // Only remove 'accepted' if no invoices are using it
        $count = \DB::table('invoices')->where('status', 'accepted')->count();
        if ($count == 0) {
            \DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('unpaid', 'paid', 'overdue') DEFAULT 'unpaid'");
        }
    }
}
