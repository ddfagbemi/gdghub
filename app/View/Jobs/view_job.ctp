<?php
/**
  Filename: view_question.ctp
  @author: Femi TAIWO [dftaiwo@gmail.com]
  Created: Feb 14, 2013  6:42:47 AM
 */
//$isAnswerable = ($question['Question']['flag'] == 0);
//echo $_thisUserId;
//echo '<br />';
//echo $question['Question']['user_id'];
?>
<?php echo $this->element('breadcrumb'); ?> 
<div class="row">
      <div class="span9">
            <div class="bordered">
                  <h1 class="questionTitle">
                        <?php echo $job['Job']['name']; ?>
                  </h1>
                  <div class="questionQuestion">
                        <?php
                        $fullDescription = $job['Job']['description'];

                        echo $this->Jv->highlight($fullDescription);
                        ?>
                  </div>
                 <?php if($job['Job']['registration_link'] != "") { 
		 echo "<div><br /><a href =". $job['Job']['registration_link'] ." target='_blank'>Click here to register</a></div>";
                  } ?>
                  <div class="floatRight posterInfo">
                        <?php echo $this->element('user/basic', array('user' => $job['User'])); ?>
                        <br />Posted @ <?php echo $this->JV->longTime($job['Job']['created']); ?>
                  </div>
                
                  <hr />
		<strong>Required Skills:</strong>
                  <div id="selectedSkills" class="userSkills">
	            <div class="userSkills">
                         <?php
                        foreach ($jobSkillSets as $skillRow) {
                              ?><span><?php echo $skillRow['Skillset']['name']; ?>
                              </span>
                                    <?php
                              }
                              ?></div>

                       <div class="clear"></div>
		   </div>
                </div>
	</div>

        <div class="span3">
                <div class="bordered">
                        <h4>Latest Jobs</h4>
			 <?php if (!$latest_jobs) { ?>
                                <div class="alert alert-danger">
                                        Unfortunately there are no Jobs.
                                </div>
                        <?php } else{ ?>
                        <?php
                        foreach ($latest_jobs as $latest_job) {
                        	$latest_jobId = $latest_job['Job']['id'];
                        ?>
                        <div class="postedQuestion">

                        <span class="questionTitle"><?php echo $this->Html->link($latest_job['Job']['name'], "viewJob/{$latest_job['Job']['id']}/{$latest_job['Job']['slug']}"); ?></span>
                                                        <?php echo $this->Html->link($this->Jv->shortenText($latest_job['Job']['description']),"viewJob/{$latest_job['Job']['id']}/{$latest_job['Job']['slug']}",array('escape'=>false,'class' => 'textLikeLink')); ?>
							 <span class="threadLink"><?php echo $this->Html->link('More', "viewJob/{$latest_job['Job']['id']}/{$latest_job['Job']['slug']}"); ?></span>
                                                        <div class="clear"></div>       
                                                        
                                                        <div class="clear"></div>

                                                </div>

                       <?php }
		       }
			?>
                </div>
        </div>
</div>

<?php echo $this->element('syntax_highlighter'); ?>
