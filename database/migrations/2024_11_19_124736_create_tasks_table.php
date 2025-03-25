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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('building')->nullable()->unique();
            $table->string('district')->nullable();
            $table->string('area')->nullable();
            $table->text('assigned_time')->nullable();
            $table->string('customer_name')->nullable()->unique();
            $table->string('customer_username')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->text('customer_address')->nullable();
            $table->text('splitting')->nullable();
            $table->text('sspl_no_planned')->nullable();
            $table->integer('rspl_no')->nullable();
            $table->string('through')->nullable();
            $table->string('core_color')->nullable();
            $table->text('note')->nullable();
            $table->date('date_of_task');
            $table->time('time')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->text('note_of_reject')->nullable();
            $table->text('note_of_task_that_need_approve')->nullable();
            $table->double('price_offer_from_engineer')->nullable();
            $table->string('photo_of_reject')->nullable();

            $table->enum('job_order_status', ['opened','بحاجة لكشف مهندس','تأجيل بنفس اليوم','تأجيل ليوم اخر', 'بحاجة عرض سعر', 'الغاء المعاملة','بحاجة لاعادة تخطيط','completed','delivered','rejected'])->default('opened');
            $table->enum('contractor_status', ['none','opened','pending', 'in_progress', 'completed'])->default('none');
            $table->enum('customer_service_status', ['installation','availability'])->default('installation');
            $table->enum('postal_code_status', ['active','planned'])->default('active');

            $table->tinyInteger('return_to_contractor')->nullable(); // 1 yes

            // releation

            $table->unsignedBigInteger('admin_id')->nullable(); // assigned to
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');

            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('cascade');

            $table->unsignedBigInteger('job_order_type_id');
            $table->foreign('job_order_type_id')->references('id')->on('job_order_types')->onDelete('cascade');

            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
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
        Schema::dropIfExists('tasks');
    }
};
