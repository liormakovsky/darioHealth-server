<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Cdr;
use App\Models\User;
use App\Models\Country;
use App\Models\Number;
use App\Models\SendLog;
use DB;
use Carbon\Carbon;


class MessageController extends BaseController
{   

    /**
     * getting aggregated by date information from the send_log.
     * Function receive parameters:
     * startDate
     * endDate
     * countryId (optional)
     * userId (optional)
     * return results: amount of successfully sent messages and
     * amount of failed messages.
     */
    public function getMessages(Request $request){
        
        $validator = Validator::make($request->all(), [
            'startDate' => 'required',
            'endDate' => 'required',
            'countryId' => 'sometimes',
            'userId' => 'sometimes',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(),403);       
        }
        
        $startDate = '2022-04-01';
        $endDate = Carbon::today();
        $countryId = '';
        $userId = '';

        if(!empty($request->startDate)){
            $startDate = $request->startDate;
        }

        if(!empty($request->endDate)){
            $endDate = $request->endDate;
        }

        if(!empty($request->countryId)){
            $countryId = $request->$countryId;
        }

        if(!empty($request->userId)){
            $userId = $request->userId;
        }
 
        $totals = DB::table('send_log AS s')
        ->selectRaw('s.log_created as date,
        SUM(CASE WHEN s.log_success = 1 THEN 1 ELSE 0 END) AS total_success,
        SUM(CASE WHEN s.log_success = 0 THEN 1 ELSE 0 END) AS total_failed')
        ->join('numbers as n','n.num_id','s.num_id')
        ->join('users as u', 'u.usr_id', 's.usr_id')
        ->join('countries as c', 'c.cnt_id', 's.num_id')
        ->where('s.log_created' ,'>=',$startDate)
        ->where('s.log_created','<=',$endDate)
        ->when($countryId, function ($query, $countryId) {
            return $query->where('c.cnt_id', $countryId);
        })
        ->when($userId, function ($query, $userId) {
            return $query->where('u.usr_id', $userId);
        })
        ->groupBy('s.log_created')
        ->get();
        return $this->sendResponse($totals, '');
    }                  
}

    