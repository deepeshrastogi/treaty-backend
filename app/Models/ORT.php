<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ORT extends Model {
    protected $connection = 'mysql2';
    protected $table = 'ort';
    public $timestamps = false;
    use HasFactory;

    /**
     * Get All Subsidary list by Mdt
     * @param  int, mdt_id
     * @return [json] ort array,
     */

    public function getAllOrtByMdt($mdt) {
        $allOrt = Markt::selectRaw("markt.id, CONCAT(markt.markt_nr, ' - ',ort.ort) AS 'ort', markt.markt_nr + ort.ort
        AS 'relevance'")
            ->join('ort', function ($join) use ($mdt) {
                $join->on('ort.id', '=', 'markt.ort');
                $join->where('markt.mdt', $mdt);
            })
            ->orderBy('relevance', 'desc')
            ->get();
        return $allOrt;
    }

    /**
     * Get All Subsidary list
     * @return [json] ort array,
     */

    public function getAllOrt() {
        $allOrt = Markt::selectRaw("markt.id, CONCAT(markt.markt_nr, ' - ',ort.ort) AS 'ort',
        markt.markt_nr + ort.ort
        AS 'relevance'")
            ->join('ort', 'ort.id', '=', 'markt.ort')
            ->orderBy('relevance', 'desc')
            ->get();
        return $allOrt;
    }

    /**
     * Search subsidary list by mdt
     * @param  object serach,
     * @param int mdt id
     * @return [json] ort array,
     */
    public function searchOrtByMdt($mdt, $search_keys) {
        $resultOrt = Markt::selectRaw("markt.id, CONCAT(markt.markt_nr, ' - ',ort.ort) AS
         'ort', IFNULL((markt.markt_nr REGEXP '{$search_keys}'),0) + IFNULL((ort.ort REGEXP '{$search_keys}'),0) AS 'relevance'")
            ->join('ort', function ($join) use ($mdt) {
                $join->on('ort.id', '=', 'markt.ort');
                $join->where('markt.mdt', $mdt);
            })
            ->whereRaw("markt.markt_nr REGEXP '{$search_keys}' OR ort.ort REGEXP '{$search_keys}'")
            ->orderBy('relevance', 'desc')
            ->get();
        return $resultOrt;
    }

    /**
     * Search subsidary list by mdt
     * @param  object serach,
     * @return [json] subsidary array,
     */
    public function searchOrt($search_keys) {

        $resultOrt = Markt::selectRaw("markt.id, CONCAT(markt.markt_nr, ' - ',ort.ort) AS
         'ort', IFNULL((markt.markt_nr REGEXP '{$search_keys}'),0) + IFNULL((ort.ort REGEXP
          '{$search_keys}'),0) AS 'relevance'")
            ->join('ort', 'ort.id', '=', 'markt.ort')
            ->whereRaw("markt.markt_nr REGEXP '{$search_keys}' OR ort.ort REGEXP '{$search_keys}'")
            ->orderBy('relevance', 'desc')
            ->get();
        return $resultOrt;
    }
}
