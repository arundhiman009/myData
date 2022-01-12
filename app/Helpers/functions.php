<?php

use Carbon\Carbon;

if (!function_exists('casinoDate')) {
    function casinoDate($date)
    {
        $timestamp = \Carbon\Carbon::parse($date)->timestamp;
        return '<span class="d-none">' . $timestamp . '</span>' . Carbon::parse($date)->format('m/d/Y');
    }
}
if (!function_exists('casinoDateTime')) {
    function casinoDateTime($date)
    {
        $timestamp = \Carbon\Carbon::parse($date)->timestamp;
        return '<span class="d-none">' . $timestamp . '</span>' . Carbon::parse($date)->format('m/d/Y g:i A');
    }
}
if (!function_exists('casinoTime')) {
    function casinoTime($time)
    {
        $timestamp = \Carbon\Carbon::parse($time)->timestamp;
        return '<span class="d-none">' . $timestamp . '</span>' . Carbon::parse($time)->format('g:i A');
    }
}

if (!function_exists('amountFormat')) {
    /**
     * @param mixed $amount to be return in currency format
     * @param mixed $decimal number of decimal places default 0
     * @param mixed $symbol currency symbol default $
     */
    function amountFormat($amount, $decimal = 0, $symbol = '$')
    {
        return $symbol.number_format($amount,$decimal);
    }
}

if (!function_exists('getAmountFromFixedPercentage')) {
    /**
     * @param mixed $originalAmt is the amount comming from fromend
     * @param mixed $givenValue is the percentage/fixed amount
     * @param mixed $method type either Percentage/Fixed 0 Fixed, 1 Percentage
     */
    function getAmountFromFixedPercentage($originalAmt, $givenValue, $method = 1)
    {
        if ($method == 1) {  // 0 for Fixed 1 for percentage
            return floor(((float)$originalAmt * (float)$givenValue) / 100);
        } else {
            return  $givenValue;
        }
    }
}

if (!function_exists('getLoadMoneyStatus')) {
    /**
     * @param mixed $status is the 1 for pending, 2 for approved, 3 for hold and 4 for reject
     */
    function getLoadMoneyStatus($status = 1)
    {
        switch ($status) {
            case 1:
                return 'Processing';
                break;
            case 2:
                return 'Approved';
                break;
            case 3:
                return 'Hold';
                break;
            case 4:
                return 'Rejected';
                break;
        }
    }
}

if (!function_exists('getLoadMoneyReportStatus')) {
    function getLoadMoneyReportStatus($status = 0)
    {
        switch ($status) {
            case 0:
                return 'Outstanding';
                break;
            case 1:
                return 'Request Sent';
                break;
            case 2:
                return 'Approved';
                break;
        }
    }
}

if (!function_exists('getCashoutRequestStatus')) {
    function getCashoutRequestStatus($status = 1)
    {
        switch ($status) {
            case 0:
                return 'Processing';
                break;
            case 2:
                return 'Approved';
                break;
            case 1:
                return 'Rejected';
                break;
        }
    }
}

if (!function_exists('getStatusFromId')) {
    /**
     * @param mixed $status is the 1 for pending, 2 for approved, 3 for hold and 4 for reject
     */
    function getStatusFromId($status = 1)
    {
        switch ($status) {
            case 1:
                return 'Pending';
                break;
            case 2:
                return 'Approved';
                break;
            case 3:
                return 'Hold';
                break;
            case 4:
                return 'Reject';
                break;
        }
    }
}

if (!function_exists('getFirstLetterString')) {
    function getFirstLetterString($name) {
        return ucfirst($name[0]);
    }
}

if(!function_exists('getUnreadMessageCount'))
{
    function getUnreadMessageCount($sender_id=null)
    {
        if($sender_id)
        {
            return \App\Models\Chat::where(['receiver_id' => \Auth::user()->id, 'sender_id' => $sender_id, 'is_read' => '0'])->count();
        }
        return \App\Models\Chat::where(['receiver_id' => \Auth::user()->id, 'is_read' => '0'])->count();
    }
}

if(!function_exists('setChatDate'))
{
    function setChatDate($date = Null)
    {
        $date_formet = \Carbon\Carbon::parse($date);
        if($date_formet->isToday())
        {
            return \Carbon\Carbon::parse($date)->format('g:i A');
        }
        else if($date_formet->isYesterday())
        {
            return  'yesterday'.' '.\Carbon\Carbon::parse($date)->format('g:i A');
        }
        else
        {
            return \Carbon\Carbon::parse($date)->format('m/d g:i A');
        }
    }
}

if(!function_exists('limitString'))
{
    function limitString($string, $length)
    {
        if(strlen($string)<=$length)
        {
            return $string;
        }
        else
        {
            $sub_string = substr($string,0,$length) . '...';
            return $sub_string;
        }
    }
}