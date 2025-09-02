<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('complaint_form_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Form name like "Food Complaint"
            $table->string('slug')->unique(); // URL-friendly version
            $table->foreignId('complaint_category_id')->constrained()->onDelete('cascade');
            $table->json('form_structure'); // The actual form JSON
            $table->json('validation_rules')->nullable(); // Validation rules
            $table->string('status')->default('active'); // active, inactive
            $table->integer('sort_order')->default(0);
            $table->string('created_by')->default('Ravinx-SLIIT');
            $table->string('updated_by')->default('Ravinx-SLIIT');
            $table->timestamps();
            
            $table->index(['complaint_category_id', 'status']);
            $table->index(['slug', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('complaint_form_structures');
    }
};