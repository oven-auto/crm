<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audit_answers', function (Blueprint $table) {
            $table->boolean('positive')->default(0);
            $table->boolean('negative')->default(0);
            $table->boolean('neutral')->default(0);

            if (Schema::hasColumn('audit_answers', 'type')) {
                Schema::table('audit_answers', function (Blueprint $table) {
                    $table->dropColumn('type'); 
                });
            }

            if (Schema::hasColumn('audit_answers', 'created_at')) {
                Schema::table('audit_answers', function (Blueprint $table) {
                    $table->dropColumn('created_at'); 
                });
            }

            if (Schema::hasColumn('audit_answers', 'updated_at')) {
                Schema::table('audit_answers', function (Blueprint $table) {
                    $table->dropColumn('updated_at'); 
                });
            }

            if (Schema::hasColumn('audit_answers', 'deleted_at')) {
                Schema::table('audit_answers', function (Blueprint $table) {
                    $table->dropColumn('deleted_at'); 
                });
            }

            if (Schema::hasColumn('audit_answers', 'author_id')) {
                Schema::table('audit_answers', function (Blueprint $table) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropForeign(['author_id']); 
                    $table->dropColumn('author_id'); 
                    Schema::enableForeignKeyConstraints();
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_answers', function (Blueprint $table) {
            $table->dropColumn('positive');
            $table->dropColumn('negative');
            $table->dropColumn('neutral');
        });
    }
};
