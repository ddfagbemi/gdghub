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
			$published = $job['Job']['published'];
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
		<span class="floatLeft">
                        <?php   
				$publish_btn = "";	
				if($published) 
					{$publish_btn = "Unpublish";}
                  		else{ $publish_btn = "Publish";}
				echo $this->Html->link($publish_btn, "#", array('class' => 'btn btn-mini btn-success', 'data-toggle' => 'modal', 'data-target' => '#publishModal', 'data-remote' => $this->Html->url("publish/$jobId/{$job['Job']['slug']}")));
				echo ' ';
				echo $this->Html->link('Edit', "edit/{$job['Job']['id']}/{$job['Job']['slug']}", array('class' => 'btn btn-mini btn-primary'));
				echo ' | ';
                                echo $this->Html->link('Delete', "#", array('class' => 'btn btn-mini btn-danger', 'data-toggle' => 'modal', 'data-target' => '#deleteModal', 'data-remote' => $this->Html->url("delete/{$job['Job']['id']}/{$job['Job']['slug']}")));
                              ?>
                </span>
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

<div class="modal hide custom-width-modal" id="publishModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body">
            <!-- content will be loaded here -->
      </div>
      <div class="modal-footer">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
</div>
<div class="modal hide custom-width-modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Cancel</button>
      </div>
      <div class="modal-body">
            <!-- content will be loaded here -->
      </div>
      <div class="modal-footer">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Cancel</button>
      </div>
</div>
<div class="modal hide custom-width-modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Cancel</button>
      </div>
      <div class="modal-body">
            <!-- content will be loaded here -->
      </div>
      <div class="modal-footer">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Cancel</button>
      </div>
</div>
</div>
<script type="text/javascript">
$('#publishModal').on('hidden',function(){
	$(this).data('modal').$element.removeData();
})
</script>
<script type="text/javascript">
$('#deleteModal').on('hidden',function(){
	$(this).data('modal').$element.removeData();
})
</script>
<?php echo $this->element('syntax_highlighter'); ?>
