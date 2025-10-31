<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Use raw SQL to safely add all missing columns
        $this->safeAddColumn('is_active', 'BOOLEAN DEFAULT TRUE');
        $this->safeAddColumn('is_featured', 'BOOLEAN DEFAULT FALSE');
        $this->safeAddColumn('is_recommended', 'BOOLEAN DEFAULT FALSE');
        $this->safeAddColumn('slug', 'VARCHAR(255) NULL');
        $this->safeAddColumn('deleted_at', 'TIMESTAMP NULL');
        
        // Add indexes
        $this->safeAddIndex('products_is_active_is_featured_index', '(`is_active`, `is_featured`)');
        $this->safeAddIndex('products_category_id_index', '(`category_id`)');
    }

    public function down()
    {
        // Safe removal - only drop if exists
        $this->safeDropColumn('is_active');
        $this->safeDropColumn('is_featured');
        $this->safeDropColumn('is_recommended');
        $this->safeDropColumn('slug');
        $this->safeDropColumn('deleted_at');
        
        // Drop indexes
        $this->safeDropIndex('products_is_active_is_featured_index');
        $this->safeDropIndex('products_category_id_index');
    }
    
    private function safeAddColumn($columnName, $columnDefinition)
    {
        $columns = DB::select("SHOW COLUMNS FROM products LIKE '$columnName'");
        if (empty($columns)) {
            DB::statement("ALTER TABLE products ADD COLUMN $columnName $columnDefinition");
        }
    }
    
    private function safeDropColumn($columnName)
    {
        $columns = DB::select("SHOW COLUMNS FROM products LIKE '$columnName'");
        if (!empty($columns)) {
            DB::statement("ALTER TABLE products DROP COLUMN $columnName");
        }
    }
    
    private function safeAddIndex($indexName, $columns)
    {
        $indexes = DB::select("SHOW INDEX FROM products WHERE Key_name = '$indexName'");
        if (empty($indexes)) {
            DB::statement("CREATE INDEX $indexName ON products $columns");
        }
    }
    
    private function safeDropIndex($indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM products WHERE Key_name = '$indexName'");
        if (!empty($indexes)) {
            DB::statement("DROP INDEX $indexName ON products");
        }
    }
};