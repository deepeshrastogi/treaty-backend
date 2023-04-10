<?php
namespace App\Http\Controllers\Api\V2\Languages;

use App\Http\Controllers\Api\V2\Controller;
use App\Http\Traits\ApiResponse;
use Config;
use File;
use Illuminate\Http\Request;

class LanguagesController extends Controller {
    use ApiResponse;

    /**
     * get language data according to language .
     * @param  \Illuminate\Http\Request  $request
     * @return [json] \Illuminate\Http\Response
     */
    public function getLangugageJson(Request $request) {
        $lang = $request->header('X-localization');
        $path = resource_path('lang/' . $lang . '/' . $lang . '.json');
        if (File::exists($path)) {
            $json = json_decode(file_get_contents($path), true);
            $json['api_version'] = Config::get('api.API_VERSION');
            return $this->success($json);
        } else {
            return $this->error(trans('messages.language.error'));
        }
    }

}
