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


class MessageController extends BaseController
{   

    /**
     * get messages data from DB
     */
    public function getMessages(Request $request){
        return $this->sendResponse([], 'hello from getmessages controller');
    }
        
                     
}

    