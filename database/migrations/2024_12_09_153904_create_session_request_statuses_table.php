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
        if (!Schema::hasTable('session_request_statuses')) {
            Schema::create('session_request_statuses', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->text('description')->nullable();
                $table->string('hex_color', 7)->default('#84cc16');
                $table->boolean('deleted')->default(false);
                $table->timestamps();
                $table->boolean('is_default')->default(false);
                $table->boolean('accept')->default(false);
                $table->boolean('reject')->default(false);
                $table->boolean('complete')->default(false);
                $table->unsignedBigInteger('email_template_id')->nullable();
                $table->unsignedInteger('is_active')->default(0);

                $table->foreign('email_template_id')->references('id')->on('email_templates')->onDelete('set null')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_request_statuses');
    }
};
