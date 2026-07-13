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
		Schema::table('treating_irons', function (Blueprint $table) {
			$table->string('ntir_15')->nullable()->change();
			$table->string('ntir_16')->nullable()->change();
			$table->string('ntir_17')->nullable()->change();
			$table->string('ntir_18')->nullable()->change();
			$table->string('ntir_19')->nullable()->change();
			$table->string('ntir_20')->nullable()->change();
			$table->string('ntir_21')->nullable()->change();
			$table->string('ntir_22')->nullable()->change();
			$table->string('ntir_23')->nullable()->change();
			$table->string('ntir_24')->nullable()->change();
			$table->string('ntir_25')->nullable()->change();
			$table->string('ntir_26')->nullable()->change();
			$table->string('ntir_27')->nullable()->change();
			$table->string('ntir_28')->nullable()->change();

			// $table->string('nmpr_14')->nullable()->change();
			// $table->string('nmpr_15')->nullable()->change();
			// $table->string('nmpr_16')->nullable()->change();
			// $table->string('nmpr_17')->nullable()->change();
			// $table->string('nmpr_18')->nullable()->change();
			// $table->string('nmpr_19')->nullable()->change();
			// $table->string('nmpr_20')->nullable()->change();
			// $table->string('nmpr_21')->nullable()->change();
			// $table->string('nmpr_22')->nullable()->change();
			// $table->string('nmpr_23')->nullable()->change();
			// $table->string('nmpr_24')->nullable()->change();
			// $table->string('nmpr_25')->nullable()->change();

			$table->string('ntir_31')->nullable()->change();
			$table->string('ntir_32')->nullable()->change();
			$table->string('ntir_33')->nullable()->change();
			$table->string('ntir_34')->nullable()->change();
			$table->string('ntir_35')->nullable()->change();
			$table->string('ntir_36')->nullable()->change();
			$table->string('ntir_37')->nullable()->change();
			$table->string('ntir_38')->nullable()->change();
			$table->string('ntir_39')->nullable()->change();
			$table->string('ntir_40')->nullable()->change();
			$table->string('ntir_41')->nullable()->change();
			$table->string('ntir_42')->nullable()->change();

			$table->string('ntir_49')->nullable()->change();
			// $table->string('ntir_50')->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('treating_irons', function (Blueprint $table) {
			$table->string('ntir_15')->nullable(false)->change();
			$table->string('ntir_16')->nullable(false)->change();
			$table->string('ntir_17')->nullable(false)->change();
			$table->string('ntir_18')->nullable(false)->change();
			$table->string('ntir_19')->nullable(false)->change();
			$table->string('ntir_20')->nullable(false)->change();
			$table->string('ntir_21')->nullable(false)->change();
			$table->string('ntir_22')->nullable(false)->change();
			$table->string('ntir_23')->nullable(false)->change();
			$table->string('ntir_24')->nullable(false)->change();
			$table->string('ntir_25')->nullable(false)->change();
			$table->string('ntir_26')->nullable(false)->change();
			$table->string('ntir_27')->nullable(false)->change();
			$table->string('ntir_28')->nullable(false)->change();

			// $table->string('nmpr_14')->nullable(false)->change();
			// $table->string('nmpr_15')->nullable(false)->change();
			// $table->string('nmpr_16')->nullable(false)->change();
			// $table->string('nmpr_17')->nullable(false)->change();
			// $table->string('nmpr_18')->nullable(false)->change();
			// $table->string('nmpr_19')->nullable(false)->change();
			// $table->string('nmpr_20')->nullable(false)->change();
			// $table->string('nmpr_21')->nullable(false)->change();
			// $table->string('nmpr_22')->nullable(false)->change();
			// $table->string('nmpr_23')->nullable(false)->change();
			// $table->string('nmpr_24')->nullable(false)->change();
			// $table->string('nmpr_25')->nullable(false)->change();

			$table->string('ntir_31')->nullable(false)->change();
			$table->string('ntir_32')->nullable(false)->change();
			$table->string('ntir_33')->nullable(false)->change();
			$table->string('ntir_34')->nullable(false)->change();
			$table->string('ntir_35')->nullable(false)->change();
			$table->string('ntir_36')->nullable(false)->change();
			$table->string('ntir_37')->nullable(false)->change();
			$table->string('ntir_38')->nullable(false)->change();
			$table->string('ntir_39')->nullable(false)->change();
			$table->string('ntir_40')->nullable(false)->change();
			$table->string('ntir_41')->nullable(false)->change();
			$table->string('ntir_42')->nullable(false)->change();

			$table->string('ntir_49')->nullable(false)->change();
			// $table->string('ntir_50')->nullable(false)->change();
		});
	}
};
