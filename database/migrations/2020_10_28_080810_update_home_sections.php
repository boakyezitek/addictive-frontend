<?php

use App\Models\HomeTemplates\CollectionTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\HomeSection;

class UpdateHomeSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_sections', function(Blueprint $table) {
            $table->renameColumn('type', 'template')->default(CollectionTemplate::$key);
            $table->string('title')->after('id')->nullable();
            $table->integer('order')->nullable()->change();
            $table->dropMorphs('homable', 'homable_index');
            $table->integer('home_sectionable_id')->unsigned()->nullable()->after('order');
            $table->string('home_sectionable_type')->nullable()->after('home_sectionable_id');
            $table->index(['home_sectionable_id', 'home_sectionable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_sections', function(Blueprint $table) {
            $table->renameColumn('template', 'type');
            $table->dropColumn('title');
            $table->dropMorphs('home_sectionable');
            $table->morphs('homable');
        });
    }
}
