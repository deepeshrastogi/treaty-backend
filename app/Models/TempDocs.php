<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class TempDocs extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    
    protected $table = 'temp_docs';
    protected $connection = 'mysql';
    protected $hidden = ['pivot'];
    protected $fillable = ['doc_original_name', 'doc_temp_name', 'url', 'file_size', 'file_type'];
    public function __construct() {
        $this->table = DB::connection($this->connection)->getDatabaseName() . '.' . $this->table;
    }
    
}
