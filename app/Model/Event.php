<?php

/**
 * Filename: Event.php
 * @author: Damilare Fagbemi [damilarefagbemi@gmail.com]
 * Created: Mar 21, 2013  11:32:25 AM 
 */

class Event extends AppModel {

        /**
         * Display field
         *
         * @var string
         */
        public $displayField = 'name';

        public $actsAs = array('Sluggable' => array('label' => 'name', 'overwrite' =>true));
        //Overwrite set to false because if the event is edited, the links indexed by search engines will be broken
        //The alternative is to always pass the id which I find uninteresting
        //The second alternative is to create a permalinks tag for search engines...is that possible?

        //The Associations below have been created with all possible keys, those that are not needed can be removed

        /**
         * belongsTo associations
         *
         * @var array
         */
        public $belongsTo = array(
            'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id',
                'conditions' => '',
                'fields' => array('User.id','User.name','User.image'),
                'order' => ''
            )
        );

	function addEvent($eventData){
                
                $this->create($eventData);
                
                if(!$this->save($eventData)){
                        return false;
                }
                
                return $this->id;
        }

	function setEventStatus($eventData,$eventId){
	       $this->read(null,$eventId);
	       $this->set('published',$eventData);
	       $this->save();
        }

	function editEvent($eventData,$eventId){
	      
 	       $this->read(null,$eventId);
	       $this->set( array(
			'name' => $eventData['name'],
			'description' => $eventData['description'],
			'venue' => $eventData['venue'],
			'start' => $eventData['start'],
			'end' => $eventData['end'],
			'registration_link' => $eventData['registration_link'],

	       ));
	       $this->save();
//               $query="UPDATE events set published=$eventData where id='$eventId'";
  //             $this->query($query);
        }

	function getSlug($eventId=0){
                
                $conditions = array('Event.id'=>$eventId);
                return $this->field('slug',$conditions);
                
        }

        function getEvent($eventId=0){
                
                return $this->read(null,$eventId);
                
        }
        
        function increaseViewCount($eventId){
                
                $query="UPDATE events set views=views+1 where id='$eventId'";
                $this->query($query);
                
        }

}
?>
