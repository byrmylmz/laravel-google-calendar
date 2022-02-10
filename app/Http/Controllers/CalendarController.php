<?php

namespace App\Http\Controllers;

use App\Services\Google;
use App\Models\Calendar;
use App\Models\Event;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function createEvent(Request $request, Google $google) {

        $token= auth()->user()->googleAccounts()->first()->token;

        $service = $google->connectUsing($token)->service('calendar');
        
        // Print the next 10 events on the user's calendar.
        $calendarId = 'alakodcontact@gmail.com';

        $event = new \Google_Service_Calendar_Event(array(
            'summary' => 'Google I/O 2015 more about Google\'s developer products.',
            'start' => array(
              'dateTime' => '2022-02-10T09:00:00-02:00',
              'timeZone' => 'Europe/Istanbul',
            ),
            'end' => array(
              'dateTime' => '2022-02-10T05:00:00-07:00',
              'timeZone' => 'Europe/Istanbul',
            ),
          ));

          
          $results = $service->events->insert($calendarId,$event);
          printf('Event created: %s\n', $results->id);
          
          
          auth()->user()->googleAccounts()->first()->calendars()->first()->events()->updateOrCreate(
              [
                'google_id' => $results->id,
              ],
              [
                  'name' => 'bayram',
                  'description' => $event['summary'],
                
                  'started_at' => Carbon::parse('2022-02-09T05:00:00-07:00')->setTimezone('UTC'), 
                  'ended_at' => Carbon::parse('2022-02-09T05:00:00-07:00')->setTimezone('UTC'), 
              ]
          );
        }
    }
    