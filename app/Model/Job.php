<?php

/**
 * Filename: Job.php
 * @author: Damilare Fagbemi [damilarefagbemi@gmail.com]
 * Created: Mar 15, 2013  4:23:25 PM 
 */

class Job extends AppModel {

        /**
         * Display field
         *
         * @var string
         */
        public $displayField = 'name';

        public $actsAs = array('Sluggable' => array('label' => 'name', 'overwrite' =>true));
        //Overwrite set to false because if the job is edited, the links indexed by search engines will be broken
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

	function addJob($jobData){
                
                $this->create($jobData);
                
                if(!$this->save($jobData)){
                        return false;
                }
                
                return $this->id;
        }

	function getSlug($jobId=0){
                
                $conditions = array('Job.id'=>$jobId);
                return $this->field('slug',$conditions);
                
        }

        function getJob($jobId=0){
                
                return $this->read(null,$jobId);
                
        }
        
        function increaseViewCount($jobId){
                
                $query="UPDATE jobs set views=views+1 where id='$jobId'";
                $this->query($query);
                
        }


	function setJobStatus($jobData,$jobId){
	       $this->read(null,$jobId);
	       $this->set('published',$jobData);
	       $this->save();
        }

	function editJob($jobData,$jobId){
	      
 	       $this->read(null,$jobId);
	       $this->set( array(
			'name' => $jobData['name'],
			'description' => $jobData['description'],
			'registration_link' => $jobData['registration_link'],

	       ));
	       $this->save();
//               $query="UPDATE events set published=$eventData where id='$eventId'";
  //             $this->query($query);
        }


}
?>
