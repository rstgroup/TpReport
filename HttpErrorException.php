<?php

namespace TpReport;

/**
 * @see http://dev.targetprocess.com/rest/response_format#code
 */
class HttpErrorException extends Exception {
    

    public function __construct($code) {
      
        switch ($code) {
            case 400:
                $message = "Bad format. Incorrect parameter or query string.";
                break;
            case 401:
                $message = "Unauthorized. Wrong or missed credentials.";
                break;
            case 403:
                $message = "Forbidden. A user has insufficient rights to perform an action.";
                break;
            case 404:
                $message = "Requested Entity not found.";
                break;
            case 500:
                $message = "Internal server error. TargetProcess messed up.";
                break;
            case 501:
                $message = "Not implemented. The requested action is either not supported or not implemented yet.";
                break;
            default:
                $message = '';
                break;
        }
        
      parent::__construct($message, $code, NULL);
      
}
    
}
