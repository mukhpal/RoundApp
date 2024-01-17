<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceView extends Migration
{
    public $view = "balance";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createView();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropView();
    }

    public function createView()
    {
        $sql = <<<SQL
CREATE OR REPLACE VIEW {$this->view} AS (
    SELECT
        user_id,
        SUM(amount) AS amount
    FROM accountings
    GROUP BY
        user_id
)
SQL;
        DB::statement($sql);
    }

    public function dropView()
    {
        $sql = <<<SQL
DROP VIEW IF EXISTS {$this->view}
SQL;
        DB::statement($sql);
    }
}
