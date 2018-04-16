<?php

namespace Revlv;

use Illuminate\Database\Eloquent\Model;

use Revlv\Users\UserRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use Revlv\Chats\Message\MessageRepository;
use Revlv\Chats\ChatRepository;
use \Revlv\Users\Logs\UserLogRepository;
use Carbon\Carbon;
use Revlv\Events\NotificationRepository;

class BaseComposer
{

    /**
     * [$user description]
     *
     * @var [type]
     */
    private $user;
    private $upr;
    private $holidays;
    private $logs;
    private $chats;
    private $nofications;

    /**
     * @param Model $model
     * @param Department Model $model
     */
    public function __construct(
        UserRepository $user,
        UnitPurchaseRequestRepository $upr,
        UserLogRepository $logs,
        ChatRepository $chats,
        MessageRepository $messages,
        NotificationRepository $nofications,
        HolidayRepository $holidays)
    {
        $this->user     =   $user;
        $this->chats    =   $chats;
        $this->logs     =   $logs;
        $this->upr      =   $upr;
        $this->holidays =   $holidays;
        $this->messages =   $messages;
        $this->nofications =   $nofications;
    }

    /**
     * [getDelays description]
     *
     * @return [type] [description]
     */
    public function getDelays()
    {
        $holiday_lists  =   $this->holidays->lists('id','holiday_date');
        $h_lists        =   [];
        foreach($holiday_lists as $hols)
        {
            $h_lists[]  =   Carbon::createFromFormat('!Y-m-d', $hols)->format('Y-m-d');
        }

        $delays         =   $this->upr->getDelays();
        $newCollection  =   collect([]);
        $today          =   Carbon::now()->format('Y-m-d');
        $today          =   Carbon::createFromFormat('!Y-m-d', $today);

        foreach($delays as $key => $item)
        {
            $upr_created    = Carbon::createFromFormat('!Y-m-d H:i:s', $item->upr_created_at);

            if($item->next_due)
            {
                $next_due       = Carbon::createFromFormat('!Y-m-d', $item->next_due);
            }
            else
            {
                $next_due       = $item->date_prepared;
            }
            $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
            }, $next_due);

            if($item == null || $days > $item->next_allowable)
            {
                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_processed,
                    'state'             =>  $item->state,
                    'event'             =>  ($item->next_step) ? $item->next_step  : "Processing UPR",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $next_due->format('Y-m-d'),
                ];
                $newCollection->push($data);
            }

        }


        return $newCollection;
    }

    /**
     * @param $view
     */
    public function compose($view)
    {

        $userId         =   \Sentinel::getUser()->id;

        $userModel      =   $this->user->findById($userId);

        $delays         =   $this->getDelays();
        $logs           =   $this->logs->findUnSeedByAdmin($userId);

        $notifications  =   $this->nofications->getUnseenByUser($userId);
        $messagesCount  =   $this->messages->getUnseenByUser($userId);
        $myMessages     =   $this->chats->findByReceiver($userId);
        $delayCount     =   count($delays);
        $notiCount     =   count($notifications);
        $messagesCount     =   count($messagesCount);
        if($myMessages) {
            $myMessages     =   count($myMessages);
        } else {
            $myMessages     =   0;
        };
        if($logs){
            $logCounts     =   0;
        } else{
            $logCounts     =   count($logs);
        }

        if(count($delays) > 9)
        {
            $delayCount = '9+';
        }

        if(count($notifications) > 9)
        {
            $notiCount = '9+';
        }

        if($messagesCount > 9)
        {
            $messagesCount = '9+';
        }

        if($myMessages > 9)
        {
            $myMessages = '9+';
        }


        if($logCounts > 9)
        {
            $logCounts = '9+';
        }

        $view->with('logCounts', $logCounts );
        $view->with('delayCounts', $delayCount );
        $view->with('notifCount',  $notiCount);
        $view->with('messageCount', $messagesCount );
        $view->with('currentUser', $userModel);
        $view->with('myMessages',  $myMessages);
    }
}
