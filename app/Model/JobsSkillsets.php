<?php

App::uses('AppModel', 'Model');

/**
 * UsersSkill Model
 *
 * @property User $User
 * @property Skillset $Skillset
 */
class JobsSkillsets extends AppModel {

      public $useTable='jobs_skillsets';
        /**
         * Primary key field
         *
         * @var string
         */
        public $primaryKey = 'skillset_id';
        public $displayField = 'skillset_id';


        //The Associations below have been created with all possible keys, those that are not needed can be removed

        /**
         * belongsTo associations
         *
         * @var array
         */
        public $belongsTo = array(
//            'User' => array(
//                'className' => 'User',
//                'foreignKey' => 'user_id',
//                'conditions' => '',
//                'fields' => '',
//                'order' => ''
//            ),
            'Skillset' => array(
                'className' => 'Skillset',
                'foreignKey' => 'skillset_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
            )
        );

	function addJobSkill($data){
            $this->create($data);
            $this->save($data);
      }

        function getJobSkills($jobId){
                
                return $this->find('all',array('conditions'=>array('JobsSkillsets.job_id'=>$jobId)));
                
        }
        function listJobSkillIds($userId){
                
                return $this->find('list',array('conditions'=>array('JobsSkillsets.job_id'=>$jobId)));
                
        }
        function removeSkill($jobId,$skillId){
              $conditions=  array(
                  'JobsSkillsets.skillset_id'=>$skillId,
                  'JobsSkillsets.job_id'=>$jobId
              );
              
              $this->deleteAll($conditions);
              
        }
        function removeAllJobSkills($jobId){
              $conditions=  array(
                  'JobsSkillsets.job_id'=>$jobId,
              );
              
              $this->deleteAll($conditions);
              
        }
}
