<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('UPDATE jobs SET employer_id = (SELECT employers.id FROM employers WHERE employers.user_id = jobs.employer_id) WHERE EXISTS (SELECT 1 FROM employers WHERE employers.user_id = jobs.employer_id)');

        Schema::table('jobs', function (Blueprint $table) {
            if (collect(Schema::getForeignKeys('jobs'))->contains(fn (array $foreignKey) => $foreignKey['columns'] === ['employer_id'])) {
                $table->dropForeign(['employer_id']);
            }
            $table->foreign('employer_id')->references('id')->on('employers')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        DB::statement('UPDATE jobs SET employer_id = (SELECT employers.user_id FROM employers WHERE employers.id = jobs.employer_id) WHERE EXISTS (SELECT 1 FROM employers WHERE employers.id = jobs.employer_id)');

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign(['employer_id']);
            $table->foreign('employer_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};