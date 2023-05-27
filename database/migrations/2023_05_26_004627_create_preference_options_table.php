<?php

use App\Services\News\ScrapPreferencesService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('preference_options', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->enum("type", ["source", "category", "author"]);
            //Name must be unique with the category in order to avoid the duplications
            $table->timestamps();
            $table->unique(['name', 'type']);
        });
        //scrap data samples from the data source and feed the table
        $scrapPreferencesService = new ScrapPreferencesService();
        $scrapPreferencesService->feedDatabase();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preference_options');
    }
};
