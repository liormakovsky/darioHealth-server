<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\Number;
use App\Models\SendLog;
use DB;
use Carbon\Carbon;


class MessageController extends BaseController
{   
    /**
     * get countries and users data for the select inputs
     * return collection
     */
    public function getInputsValues(Request $request){
        $countries = Country::select('cnt_id','cnt_title')->get();
        $users = User::select('id','usr_name')->get();
        if($countries->isEmpty() || $users->isEmpty()){
            return $this->sendError('Please execute the seeders', [],200);
        }
        return $this->sendResponse([$countries,$users], '');
    }

    /**
     * getting aggregated by date information from the send_log.
     * Function receive parameters:
     * startDate
     * endDate
     * countryId (optional)
     * userId (optional)
     * return collection results: amount of successfully sent messages and
     * amount of failed messages.
     */
    public function getTotalMessages(Request $request){
        
        /*$validator = Validator::make($request->all(), [
            'startDate' => 'required',
            'endDate' => 'required',
            'countryId' => 'sometimes',
            'userId' => 'sometimes',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(),403);       
        }*/
        $totals = [];
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
        ->join('users as u', 'u.id', 's.usr_id')
        ->join('countries as c', 'c.cnt_id', 's.num_id')
        ->where('s.log_created' ,'>=',$startDate)
        ->where('s.log_created','<=',$endDate)
        ->when($countryId, function ($query, $countryId) {
            return $query->where('c.cnt_id', $countryId);
        })
        ->when($userId, function ($query, $userId) {
            return $query->where('u.id', $userId);
        })
        ->groupBy('s.log_created')
        ->get();
        return $this->sendResponse($totals, '');
    }                  
}

    