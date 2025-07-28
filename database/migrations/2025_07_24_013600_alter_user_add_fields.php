<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(User::TABLE, function (Blueprint $table) {
            $table->string('first_name', 20)->after('id');
            $table->string('last_name', 50)->after('first_name');
            // Add new fields
            $table->boolean('blocked')->default(false);
            $table->boolean('administrator')->default(false);
            $table->unsignedInteger('attempts')->default(0);

            $table->string('cpf', 11)->unique();
            $table->string('position', 50);
            $table->date('date_of_birth');
            $table->string('cep', 8);
            $table->string('address', 80);
            $table->string('number', 10);
            $table->string('complement', 20)->nullable();
            $table->string('district', 50);
            $table->string('city', 50);
            $table->string('state', 2);

            $table->unsignedBigInteger('manager_id')->nullable();
            $table->foreign('manager_id')
                ->references('id')
                ->on(User::TABLE)
                ->onDelete('set null');

            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(User::TABLE, function (Blueprint $table){
            $table->string('name')->after('id');
            // Drop newly added columns
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['first_name', 'last_name', 'blocked', 'administrator', 'attempts', 'manager_id']);
            $table->dropColumn(['document', 'cargo', 'birth_date', 'cep', 'address', 
                    'number', 'complement', 'district', 'city', 'state']);
        });
    }
};