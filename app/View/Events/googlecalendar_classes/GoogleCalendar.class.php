<?php

/**
 * Description of GoogleCalendar
 * @package add2googlecal
 * @author johnish
 * @version 1.01
 */
class GoogleCalendar {

    // http://www.google.com/googlecalendar/event_publisher_guide_detail.html
    //characters that are not allowed in CGI parameter values
    // http://support.google.com/calendar/bin/answer.py?hl=en&answer=37292
    private $disallowed = array('=',);
    public $eventTitle, $eventStartTime, $eventEndTime, $eventLocation;
    //http://www.google.com/googlecalendar/event_publisher_guide.html
    //example link to create google calendar
    // <a href="http://www.google.com/calendar/event?action=TEMPLATE&text=Team%201%20vs%202
    // &dates=20120209T010000Z/20120209T020000Z&details=&location=Rustin%20Bayard%20Gym%207th%20Floor
    // &trp=true&sprop=www.gothamvolleyball.org&sprop=name:Gotham%20Volleyball" target="_blank">
    // <img src="//www.google.com/calendar/images/ext/gc_button1.gif" alt="0" border="0"></a>

    /**
     * Constructor
     * @param array $config 
     */
    public function __construct() {
        //set local timezone
        date_default_timezone_set("America/New_York");

        $this->_config = array('url' => 'http://www.google.com/calendar/event?',
            'title' => '',
            'datetime' => array('start' => '2012-12-01 00:00', 'end' => '2012-12-01 00:00'),
            'location' => '',
            'description' => '',
            'appendToURL' => 'action=TEMPLATE&amp;trp=true&amp;sprop=www.gothamvolleyball.org&amp;sprop=name:Gotham%20Volleyball',
            'linkTarget' => '_blank',
            'showImage' => 'true',
            'showText' => 'false',
            'linkImage' => '//www.google.com/calendar/images/ext/gc_button1.gif',
            'linkText' => '+calendar',
            'target' => '_blank',
            'websitename' => '',
            'websiteurl'  => '',
        );
//    parent::__construct($config + $defaults);
    }

    /**
     * kind of like a rename, instead of config['title'] use 'text'
     */
    private function configMapping() {
        $configMapping = array('title' => 'text', 'datetime' => 'dates', 'description' => 'details',);
    }

    private function formatTime($time) {
        $tempStart = strtotime($time);
        $tempStart = Dates::convert_to_timezone($tempStart, 'Universal');
        return date('Ymd\THis\Z', $tempStart);
    }

    /**
     *
     * @param array $params -the various information for the calendar event 
     *  ('title'=>'the event title goes here',
     *  'datetime'=>array('start'=> '2012-12-01 00:00', 'end' => '2012-12-01 00:00'),
     *  'sprop'=>'website:www.gothamvolleyball.org&sprop;=name: Gotham Volleyball',
     *  'add'=>'email address of the guest to invite',
     *  'details'=>'Description of the event. Simple HTML is allowed.',
     *  'location'=>'Where the event will take place',
     *  'trp'=>true/false if you want to show the user as busy during this event    
     *  'target'=> URL target (i.e. open in new window) _blank, _self, _top, _parent, (framename)
     *  'websitename' => 'John Doe\'s Blog Site',
     *  'websiteurl'  => 'http://www.johndoeblog.com',
     * @param type $returnButton 
     */
    static function createEventReminder($params, $returnButton=false) {
        $gcal = new GoogleCalendar();
        $params += $gcal->_config;
        $gcal->eventTitle = $params['title'];
        $gcal->eventStartTime = $gcal->formatTime($params['datetime']['start']);
        $gcal->eventEndTime = $gcal->formatTime($params['datetime']['end']);
        $gcal->eventLocation = $params['location'];
/*
        return '<a title="Add to My Google Calendar" class="addtogooglecalendar" target="' . $params['target'] . '" href="' . $params['url'] . 'action=TEMPLATE&text=' . $gcal->eventTitle . '&dates=' . $gcal->eventStartTime . '/' . $gcal->eventEndTime . '&location=' . $gcal->eventLocation . '&details=' . $params['description'] .'&trp=true&sprop=website:'.$params['websiteurl'].'&sprop=name:'.$params['websitename'].'"><img src="//www.google.com/calendar/images/ext/gc_button2.gif"></a>';

*/

return '<a title="Add to My Google Calendar" class="addtogooglecalendar" target="' . $params['target'] . '" href="' . $params['url'] . 'action=TEMPLATE&text=' . $gcal->eventTitle . '&dates=' . $gcal->eventStartTime . '/' . $gcal->eventEndTime . '&location=' . $gcal->eventLocation . '&details=' . $params['description'] .'&trp=true&sprop=website:'.$params['websiteurl'].'&sprop=name:'.$params['websitename'].'">Add to calendar</a>';

/*
        return '<a title="Add to My Google Calendar" class="addtogooglecalendar" target="' . $params['target'] . '" href="' . $params['url'] . 'action=TEMPLATE&text=' . $gcal->eventTitle . '&dates=' . $gcal->eventStartTime . '/' . $gcal->eventEndTime . '&location=' . $gcal->eventLocation . '&details=' . $params['description'] .'&trp=true&sprop=website:'.$params['websiteurl'].'&sprop=name:'.$params['websitename'].'"><img src="googlecalendar_classes/calendar-42.png" alt="Add to calendar">Add to calendar</a>';
*/

    }

    /* Instructions for making Google Calendar event reminder buttons
     * http://www.google.com/googlecalendar/event_publisher_guide_detail.html
      Parameter Name	 Value	 Example
      action (required)	 This value is always TEMPLATE (all capitalized).	action=TEMPLATE
      text (required)	 Event title.                                           text=Brunch at Java Cafe
      dates (required)	 Date and time of the event, in UTC format. Append a capitalized letter “Z” to the end of times. Google Calendar will interpret the date and time for the user’s time zone.	dates=20060415/20060415 for all day, April 15th 2006
      dates=20060415T180000Z/20060415T190000Z for April 15th 2006 11:00am - noon Pacific Time
      sprop (required)	 Information to identify your organization, like your website address. Multiple sprop parameters are allowed. This information should be specified as type:value. The colon character should only be used to separate type and value.	sprop=website:www.javacafebrunches.com
      for website = www.javacafebrunches.com                                      sprop=website:www.javacafebrunches.com&sprop;=name:Java Cafe for website = www.javacafebrunches.com and name = Java Cafe
      add	 Email address of the guest to invite. Multiple add parameters are allowed.
      add=username1@domain.comfor one guest
      add=username1@domain.com&add;=username2@domain.com for two guests
      details	 Description of the event. Simple HTML is allowed.              details=Try our Saturday brunch special:<br><br>French toast with fresh fruit<br><br>Yum!
      location	 Where the event will take place. Locations that work as Google Maps queries are recommended.
      location=Java Cafe, San Francisco, CA
      trp	 Specifies whether the user’s Google Calendar shows as “busy” during this event. The default value is false.
      trp=true
      Example HTML for a Google Calendar event reminder button

      Here is what the CGI parameters would look like for brunch reservations:

      action=TEMPLATE
      text=Brunch at Java Cafe
      location=Java Cafe, San Francisco, CA
      details=Try our Saturday brunch special:<br><br>French
      toast with fresh fruit<br><br>Yum!
      dates=20060415T180000Z/20060415T190000Z
      trp=true
      sprop=website:http://www.javacafebrunches.com
      sprop=name:Jave Cafe
      Putting it all together, here’s the whole snippet of HTML you would add to your website to insert the Google Calendar event reminder button for this brunch reservation:

      <a href="http://www.google.com/calendar/event?action=
      TEMPLATE&text;=Brunch at Java Cafe&dates;=20060415T180000Z/
      20060415T190000Z&location;=Java Cafe, San Francisco, CA
      &details;=Try our Saturday brunch special:<br><br>French
      toast with fresh fruit<br><br>Yum!&trp;=true&sprop;=
      website:http://www.javacafebrunches.com&sprop;=name:Jave Cafe">
      <img src="//www.google.com/calendar/images/ext/
      gc_button2.gif"></a>
     * 
     * 100 x 25px Google Calendar
     * http://www.google.com/calendar/images/ext/gc_button1.gif
      114 x 36px remind me with Google Calendar
      http://www.google.com/calendar/images/ext/gc_button2.gif
      114 x 36px Add to Google Calendar
      http://www.google.com/calendar/images/ext/gc_button6.gif
     * 
     */
}

?>
