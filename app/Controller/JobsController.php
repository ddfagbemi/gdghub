<?php

/**
 * Filename: JobsController.php
 * @author: Damilare Fagbemi [damilarefagbemi@gmail.com]
 * Created: Mar 15, 2013  11:25:29 AM 
 */

class JobsController extends AppController {

      public $uses = array('Job', 'Skillset','JobsSkillsets');
      public $helpers = array('Jv');
      
      
      function beforeFilter() {
            parent::beforeFilter();
           $this->Auth->allow('*');
           $this->Auth->allow(array('index', 'viewJob'));
           $this->Auth->deny(array('post'));
      }
      

      /**
       * Landing Page for Jobs Section
       * It should display newest/popular jobs
       * 
       */
      function index($sortBy = 'hottest') {
	   $breadcrumbLinks = array(
		array(
			'label' => 'Jobs',
			'link' => 'index',
			'separator'=> '&raquo;'
		),
	    );
            switch ($sortBy) {
                  case 'newest':
                        $orderBy = array('Job.created' => 'DESC'); //Is this the best ?
                        break;
                  default:
                        $orderBy = array('Job.views' => 'DESC'); //Is this the best ?
            }

            $conditions = array(
                'Job.published' => 1
            );


            if (!empty($this->data) && isset($this->data['Search']['keywords'])) {

                  $keywords = $this->data['Search']['keywords'];

                  App::uses('Sanitize', 'Utility');
                  $keywords = Sanitize::paranoid($keywords, array(' '));
                  $keywords = explode(' ', $keywords);
                  $usableKeywords = false;
                  //This is the temporary search process.
                  //@TODO Change to a cool search feature
                  foreach ($keywords as $keyword) {

                        $keyword = trim($keyword);
                        if (strlen($keyword) < 3) {
                              $this->sFlash('');
                              continue;
                        }
                        $usableKeywords = true;
                        $conditions[] = "( Job.name LIKE '%$keyword%' OR Job.description LIKE '%$keyword%') ";
                  }

                  if (!$usableKeywords) {
                        $this->sFlash('Please use longer words for your search criteria',true);
                  }
            }


            $this->paginate = array('Job' => array(
                    'order' => $orderBy,
                    'limit' => 25,
                )
            );

            $jobs = $this->paginate('Job', $conditions);

            $jobIds = $this->Job->extractKeys($jobs);

            $latest_jobs = $this->Job->find('all', array('limit' => 5, 'order' => 'Job.created DESC','conditions'=>array(
                'Job.published' => 1
            ),));


	    $this->set(compact('jobs','latest_jobs','breadcrumbLinks'));

            //$questionsTags = $this->QTag->getIndexedTags($questionIds);

            //$this->set(compact('questions', 'questionsTags'));

//                $storedTags = $this->Session->read('storedTags');
//                if(!$storedTags){
//                        
//                }
           // $storedTags = $this->QTag->listTagsWithStats(); //We should cache this....

            //$this->set('storedTags', $storedTags);
      }

      function post() { //post a job
            $this->set('usesAutocomplete',true);

	   $breadcrumbLinks = array(
		array(
			'label' => 'Jobs',
			'link' => 'index',
			'separator'=> '&raquo;'
		),
		array(
			'label' => 'Post a Job',
			'link' => 'post',
			'separator'=> '&raquo;'
		),
	    );

 	    $latest_jobs = $this->Job->find('all', array('limit' => 5, 'order' => 'Job.created DESC','conditions'=>array(
                'Job.published' => 1
            ),));

            $this->set(compact('breadcrumbLinks','latest_jobs'));
            $this->_requireAuth();
            //$highlighterSettings = cRead('syntaxHighlighter');
            //$this->set('codeTypes', $highlighterSettings['supportedTypes']);
            $rules = array(
                'Post' => array(
                    'title' => array(
                        FV_REQUIRED => 'Please provide a title for this job',
                        FV_MIN_LENGTH => array('param' => 20, 'error' => 'The title provided is too short'),
                        FV_MAX_LENGTH => array('param' => 200, 'error' => 'The title provided is too long')
                    ),
                    'description' => array(
                        FV_REQUIRED => 'Please provide details for this job',
                        FV_MIN_LENGTH => array('param' => 50, 'error' => 'The description provided is too short'),
                        FV_MAX_LENGTH => array('param' => 5000, 'error' => 'The title provided is too long')
                    ),
		   'selSkills'=>array(
                         FV_REQUIRED=>'You must specify at least one skill here to submit'
                     ),
                )
            );

            $this->FormValidator->setRules($rules);

            if (!empty($this->data) && $this->FormValidator->validate()) {

                  App::uses('Sanitize', 'Utility');

                  $subData = $this->data['Post']; 

		  //process entered skills
		  $submittedSkillsets = $this->data['Post']['selSkills'];
                 
		  $skillsProvided = explode(',', $submittedSkillsets);
                  $skillIds = array();
                  $selectedSkilllSets  = $unidentifiedSkills = array();
                  foreach ($skillsProvided as $skill) {
			
                        $skill = Sanitize::paranoid($skill,array(' ','.','(',')','-','_'));
                        $skill = trim($skill);

                        if(!$skill) continue;
                        $skillId  = $this->Skillset->getSkillId($skill);
                        if(!$skillId){
                              $unidentifiedSkills[] = $skill;
                             // $data = array(
                             //     'name'=>$skill,
                             //     'user_id'=>$this->_thisUserId
                             // );
                              //$this->SkillsetSubmission->addSubmission($data);
                              continue;
                        }
                        $selectedSkilllSets[] = $skillId;
                  }
                    
		  $jobData = array(
                      'user_id' => $this->_thisUserId,
                      'name' => Sanitize::stripAll($subData['title']),
                      'description' => Sanitize::stripAll($subData['description']),
                      'registration_link' => Sanitize::stripAll($subData['registration_link'])
                  );


                  $jobId = $this->Job->addJob($jobData);
                  if (!$jobId) {
                        $this->sFlash("An unexpected error occurred while saving this job. Please try again later",true);
                        return;
                  }

                  //saving jobs skills
		  foreach ($selectedSkilllSets as $skillsetId) {
                        if (!$skillsetId)
                              continue;

                        $skillData = array(
                            'job_id' => $jobId,
                            'skillset_id' => $skillsetId
                        );
                        if ($this->JobsSkillsets->field('skillset_id', $skillData)) {
                              continue; //already created
                        }
                        $this->JobsSkillsets->addJobSkill($skillData);
                  } //$this->JobsSkillsets->addJobSkill($data);
		  $message = 'Job Posted';
                  if($unidentifiedSkills){
                        $unidentifiedSkills=join('<br /> - ',$unidentifiedSkills);
                        $message.="<br />However, the following skills were not recognized and were not added to required skills: <br /> - $unidentifiedSkills";
                  }
                  
                  $jobSlug = $this->Job->getSlug($jobId);
                  $this->miniFlash($message, "viewJob/$jobId/$jobSlug");
            }

	    //$mySkillSets = $this->JobsSkill->getJobsSkills($this->_thisUserId);
	    //$this->set('mySkillSets', $mySkillSets);

	    $possibleSkills = $this->Skillset->listSkillsByName();
            $this->set('possibleSkills',$possibleSkills);
           
      }
      /**
       * A security action for validating jobs - that it does exist.
       * @param string $jobId
       * @return array
       */
      private function _getJob($jobId) {

            $jobInfo = $this->Job->getJob($jobId);
            if (!$jobInfo) {

                  $this->miniFlash('Unable to find selected job. Try searching instead', 'index');
            }
            if (!$jobInfo['Job']['published']) {
                  $this->miniFlash('The selected job has been unpublished for some important reason I\'m not privy to :)', 'index');
            }
            return $jobInfo;
      }

      /**
       * A security action for validating jobs - that it does exist. This function allows for the display of unapproved events
       * @param string $jobId
       * @return array
       */
      private function _getAdminJob($jobId) {

            $jobInfo = $this->Job->getJob($jobId);
            if (!$jobInfo) {

                  $this->miniFlash('Unable to find selected job. Try searching instead', 'index');
            }
            return $jobInfo;
      }


      /**
       * Show details of a job
       * @param string $jobId, $jobSlug
       */
      public function viewJob($jobId = '', $jobSlug = '') {

           $job = $this->_getJob($jobId);

	   $breadcrumbLinks = array(
		array(
			'label' => 'Jobs',
			'link' => 'index',
			'separator'=> '&raquo;'
		),
		array(
			'label' => $job['Job']['name'],
			'link' => "viewJob/{$job['Job']['id']}/{$job['Job']['slug']}",
			'separator'=> '&raquo;'
		),
	    );

            //Let's try to increase the view count
            $jobsViewed = $this->Session->read('jobsViewed');
            if (!$jobsViewed) {
                  $jobsViewed = array();
            }
            if (!in_array($jobId, $jobsViewed)) {

                  $jobsViewed[] = $jobId;
                  $this->Session->write('jobsViewed', $jobsViewed);
                  $this->Job->increaseViewCount($jobId);
            }

 	   $latest_jobs = $this->Job->find('all', array('limit' => 5, 'order' => 'Job.created DESC','conditions'=>array(
                'Job.published' => 1
            ),));
            $this->set(compact('jobId', 'jobSlug', 'job','latest_jobs','breadcrumbLinks'));

           // if ($question['Question']['flag'] == 0) {
           //       $this->postResponse($questionId, $questionSlug); //To set values into form
           // }
            $highlighterSettings = cRead('syntaxHighlighter');
            $this->set('codeTypes', $highlighterSettings['supportedTypes']);

	  $jobSkillSets = $this->JobsSkillsets->getJobSkills($jobId);
 
          $this->set('jobSkillSets', $jobSkillSets);
      }

      function admin_index($sortBy = 'hottest') {
	   $breadcrumbLinks = array(
		array(
			'label' => 'Jobs',
			'link' => 'index',
			'separator'=> '&raquo;'
		),
	    );
            switch ($sortBy) {
                  case 'newest':
                        $orderBy = array('Job.created' => 'DESC'); //Is this the best ?
                        break;
                  default:
                        $orderBy = array('Job.views' => 'DESC'); //Is this the best ?
            }

           /* $conditions = array(
                'Job.published' => 1
            );*/


            if (!empty($this->data) && isset($this->data['Search']['keywords'])) {

                  $keywords = $this->data['Search']['keywords'];

                  App::uses('Sanitize', 'Utility');
                  $keywords = Sanitize::paranoid($keywords, array(' '));
                  $keywords = explode(' ', $keywords);
                  $usableKeywords = false;
                  //This is the temporary search process.
                  //@TODO Change to a cool search feature
                  foreach ($keywords as $keyword) {

                        $keyword = trim($keyword);
                        if (strlen($keyword) < 3) {
                              $this->sFlash('');
                              continue;
                        }
                        $usableKeywords = true;
                        $conditions[] = "( Job.name LIKE '%$keyword%' OR Job.description LIKE '%$keyword%') ";
                  }

                  if (!$usableKeywords) {
                        $this->sFlash('Please use longer words for your search criteria',true);
                  }
            }


            $this->paginate = array('Job' => array(
                    'order' => $orderBy,
                    'limit' => 25,
                )
            );

            $jobs = $this->paginate('Job');

            $jobIds = $this->Job->extractKeys($jobs);

            $latest_jobs = $this->Job->find('all', array('limit' => 5, 'order' => 'Job.created DESC','conditions'=>array(
                'Job.published' => 1
            ),));


	    $this->set(compact('jobs','latest_jobs','breadcrumbLinks'));

      }

      function admin_post() { //post a job
            $this->set('usesAutocomplete',true);

	   $breadcrumbLinks = array(
		array(
			'label' => 'Jobs',
			'link' => 'index',
			'separator'=> '&raquo;'
		),
		array(
			'label' => 'Post a Job',
			'link' => 'post',
			'separator'=> '&raquo;'
		),
	    );

 	    $latest_jobs = $this->Job->find('all', array('limit' => 5, 'order' => 'Job.created DESC','conditions'=>array(
                'Job.published' => 1
            ),));

            $this->set(compact('breadcrumbLinks','latest_jobs'));
            $this->_requireAuth();
            //$highlighterSettings = cRead('syntaxHighlighter');
            //$this->set('codeTypes', $highlighterSettings['supportedTypes']);
            $rules = array(
                'Post' => array(
                    'title' => array(
                        FV_REQUIRED => 'Please provide a title for this job',
                        FV_MIN_LENGTH => array('param' => 20, 'error' => 'The title provided is too short'),
                        FV_MAX_LENGTH => array('param' => 200, 'error' => 'The title provided is too long')
                    ),
                    'description' => array(
                        FV_REQUIRED => 'Please provide details for this job',
                        FV_MIN_LENGTH => array('param' => 50, 'error' => 'The description provided is too short'),
                        FV_MAX_LENGTH => array('param' => 5000, 'error' => 'The title provided is too long')
                    ),
		   'selSkills'=>array(
                         FV_REQUIRED=>'You must specify at least one skill here to submit'
                     ),
                )
            );

            $this->FormValidator->setRules($rules);

            if (!empty($this->data) && $this->FormValidator->validate()) {

                  App::uses('Sanitize', 'Utility');

                  $subData = $this->data['Post']; 

		  //process entered skills
		  $submittedSkillsets = $this->data['Post']['selSkills'];
                 
		  $skillsProvided = explode(',', $submittedSkillsets);
                  $skillIds = array();
                  $selectedSkilllSets  = $unidentifiedSkills = array();
                  foreach ($skillsProvided as $skill) {
			
                        $skill = Sanitize::paranoid($skill,array(' ','.','(',')','-','_'));
                        $skill = trim($skill);

                        if(!$skill) continue;
                        $skillId  = $this->Skillset->getSkillId($skill);
                        if(!$skillId){
                              $unidentifiedSkills[] = $skill;
                             // $data = array(
                             //     'name'=>$skill,
                             //     'user_id'=>$this->_thisUserId
                             // );
                              //$this->SkillsetSubmission->addSubmission($data);
                              continue;
                        }
                        $selectedSkilllSets[] = $skillId;
                  }
                    
		  $jobData = array(
                      'user_id' => $this->_thisUserId,
                      'name' => Sanitize::stripAll($subData['title']),
                      'description' => Sanitize::stripAll($subData['description']),
                      'registration_link' => Sanitize::stripAll($subData['registration_link'])
                  );


                  $jobId = $this->Job->addJob($jobData);
                  if (!$jobId) {
                        $this->sFlash("An unexpected error occurred while saving this job. Please try again later",true);
                        return;
                  }

                  //saving jobs skills
		  foreach ($selectedSkilllSets as $skillsetId) {
                        if (!$skillsetId)
                              continue;

                        $skillData = array(
                            'job_id' => $jobId,
                            'skillset_id' => $skillsetId
                        );
                        if ($this->JobsSkillsets->field('skillset_id', $skillData)) {
                              continue; //already created
                        }
                        $this->JobsSkillsets->addJobSkill($skillData);
                  } //$this->JobsSkillsets->addJobSkill($data);
		  $message = 'Job Posted';
                  if($unidentifiedSkills){
                        $unidentifiedSkills=join('<br /> - ',$unidentifiedSkills);
                        $message.="<br />However, the following skills were not recognized and were not added to required skills: <br /> - $unidentifiedSkills";
                  }
                  
                  $jobSlug = $this->Job->getSlug($jobId);
                  $this->miniFlash($message, "viewJob/$jobId/$jobSlug");
            }

	    //$mySkillSets = $this->JobsSkill->getJobsSkills($this->_thisUserId);
	    //$this->set('mySkillSets', $mySkillSets);

	    $possibleSkills = $this->Skillset->listSkillsByName();
            $this->set('possibleSkills',$possibleSkills);
           
      }


      function admin_edit($jobId = '', $jobSlug = '') { //post a job
	    //for now job skills arent updated
            $this->set('usesAutocomplete',true);

 	    $latest_jobs = $this->Job->find('all', array('limit' => 5, 'order' => 'Job.created DESC','conditions'=>array(
                'Job.published' => 1
            ),));

            $this->_requireAuth();

 	    $job = $this->_getAdminJob($jobId);
            //$highlighterSettings = cRead('syntaxHighlighter');
            //$this->set('codeTypes', $highlighterSettings['supportedTypes']);
            $rules = array(
                'Edit' => array(
                    'title' => array(
                        FV_REQUIRED => 'Please provide a title for this job',
                        FV_MIN_LENGTH => array('param' => 20, 'error' => 'The title provided is too short'),
                        FV_MAX_LENGTH => array('param' => 200, 'error' => 'The title provided is too long')
                    ),
                    'description' => array(
                        FV_REQUIRED => 'Please provide details for this job',
                        FV_MIN_LENGTH => array('param' => 50, 'error' => 'The description provided is too short'),
                        FV_MAX_LENGTH => array('param' => 5000, 'error' => 'The title provided is too long')
                    ),
		  /* 'selSkills'=>array(
                         FV_REQUIRED=>'You must specify at least one skill here to submit'
                     ),*/
                )
            );

            $this->FormValidator->setRules($rules);

            if (!empty($this->data) && $this->FormValidator->validate()) {

                  App::uses('Sanitize', 'Utility');

                  $subData = $this->data['Edit']; 

		  //process entered skills
		 /* $submittedSkillsets = $this->data['Edit']['selSkills'];
                 
		  $skillsProvided = explode(',', $submittedSkillsets);
                  $skillIds = array();
                  $selectedSkilllSets  = $unidentifiedSkills = array();
                  foreach ($skillsProvided as $skill) {
			
                        $skill = Sanitize::paranoid($skill,array(' ','.','(',')','-','_'));
                        $skill = trim($skill);

                        if(!$skill) continue;
                        $skillId  = $this->Skillset->getSkillId($skill);
                        if(!$skillId){
                              $unidentifiedSkills[] = $skill;
                             // $data = array(
                             //     'name'=>$skill,
                             //     'user_id'=>$this->_thisUserId
                             // );
                              //$this->SkillsetSubmission->addSubmission($data);
                              continue;
                        }
                        $selectedSkilllSets[] = $skillId;
                  }*/
                    
		  $jobData = array(
                      'user_id' => $this->_thisUserId,
                      'name' => Sanitize::stripAll($subData['title']),
                      'description' => Sanitize::stripAll($subData['description']),
                      'registration_link' => Sanitize::stripAll($subData['registration_link'])
                  );

		  $this->Job->editJob($jobData,$jobId); //edit job
                   
		 //resaving jobs skills after deleting them i.e editing job skills for this job
		 // $this->JobsSkillsets->removeAllJobSkills($jobId);
		 /*
		  foreach ($selectedSkilllSets as $skillsetId) {
                        if (!$skillsetId)
                              continue;

                        $skillData = array(
                            'job_id' => $jobId,
                            'skillset_id' => $skillsetId
                        );
                        if ($this->JobsSkillsets->field('skillset_id', $skillData)) {
                              continue; //already created
                        }
                        $this->JobsSkillsets->addJobSkill($skillData);
                  } 
		  */
		  $message = 'Job Updated';
                  /*if($unidentifiedSkills){
                        $unidentifiedSkills=join('<br /> - ',$unidentifiedSkills);
                        $message.="<br />However, the following skills were not recognized and were not added to required skills: <br /> - $unidentifiedSkills";
                  }*/
                  
                  $jobSlug = $this->Job->getSlug($jobId);
                  $this->miniFlash($message, "viewJob/$jobId/$jobSlug");
            }
	    $breadcrumbLinks = array(
		array(
			'label' => 'Jobs',
			'link' => 'index',
			'separator'=> '&raquo;'
		),
		array(
			'label' => "Edit {$job['Job']['name']}",
			'link' =>  "edit/{$job['Job']['id']}/{$job['Job']['slug']}",
			'separator'=> '&raquo;'
		),
	    );

	    $this->set(compact('breadcrumbLinks','latest_jobs'));
	    $this->set(compact('jobId', 'jobSlug', 'job'));

	    $possibleSkills = $this->Skillset->listSkillsByName();
            $this->set('possibleSkills',$possibleSkills);
           
      }
      /**
       * Show details of a job
       * @param string $jobId, $jobSlug
       */
      public function admin_viewJob($jobId = '', $jobSlug = '') {

           $job = $this->_getAdminJob($jobId);

	   $breadcrumbLinks = array(
		array(
			'label' => 'Jobs',
			'link' => 'index',
			'separator'=> '&raquo;'
		),
		array(
			'label' => $job['Job']['name'],
			'link' => "viewJob/{$job['Job']['id']}/{$job['Job']['slug']}",
			'separator'=> '&raquo;'
		),
	    );

            //Let's try to increase the view count
            $jobsViewed = $this->Session->read('jobsViewed');
            if (!$jobsViewed) {
                  $jobsViewed = array();
            }
            if (!in_array($jobId, $jobsViewed)) {

                  $jobsViewed[] = $jobId;
                  $this->Session->write('jobsViewed', $jobsViewed);
                  $this->Job->increaseViewCount($jobId);
            }

 	   $latest_jobs = $this->Job->find('all', array('limit' => 5, 'order' => 'Job.created DESC','conditions'=>array(
                'Job.published' => 1
            ),));
            $this->set(compact('jobId', 'jobSlug', 'job','latest_jobs','breadcrumbLinks'));

           // if ($question['Question']['flag'] == 0) {
           //       $this->postResponse($questionId, $questionSlug); //To set values into form
           // }
            $highlighterSettings = cRead('syntaxHighlighter');
            $this->set('codeTypes', $highlighterSettings['supportedTypes']);

	  $jobSkillSets = $this->JobsSkillsets->getJobSkills($jobId);
 
          $this->set('jobSkillSets', $jobSkillSets);
      }

      public function admin_publish($jobId='', $jobSlug='') {
            $this->_requireAuth();
            $job = $this->_getAdminJob($jobId);
	    /*
            $rules = array(
                'Publish' => array(
                    'answer' => array(
                        FV_REQUIRED => 'You must provide a response to submit this',
                    )
                )
            );

            $this->FormValidator->setRules($rules);*/

            if (!empty($this->data) && $this->FormValidator->validate()) {

                  $jobData = $this->data['Publish']['published'];

                  $this->Job->setJobStatus($jobData,$jobId);
		  $message = 'Job Status Updated';
                  
                  
                  $this->miniFlash($message, "index");
                  //$this->miniFlash($message);
            }
	    $jobSlug = $this->Job->getSlug($jobId);
            $this->set(compact('jobId', 'jobSlug', 'job'));
      }

 public function admin_delete($jobId, $jobSlug) {
            $this->_requireAuth();
            $job = $this->_getAdminJob($jobId);
            $jobSlug = $this->Job->getSlug($jobId);      
            if (!empty($this->data) && $this->FormValidator->validate()) {
			
                 if(!$this->Job->delete($jobId))
		 {
			$this->sFlash("An unexpected error occurred while deleting the job. Please try again later",true);
                        return;
		 }
		  $this->JobsSkillsets->removeAllJobSkills($jobId);
		  $message = 'Job Deleted';
                  
                  $jobSlug = $this->Job->getSlug($jobId);
                  $this->miniFlash($message, "index");
                  //$this->miniFlash($message);
            }
            $this->set(compact('jobId', 'jobSlug', 'job'));
      }


}
?>
