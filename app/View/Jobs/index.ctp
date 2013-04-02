<?php

/**
 * Filename: index.ctp
 * @author: Damilare Fagbemi [damilarefagbemi@gmail.com]
 * Created: Mar 15, 2013  11:31:08 AM 
 */


?>
<?php echo $this->element('breadcrumb'); ?> 
<div class="row">
        <div class="span9">
                <div class="bordered minH600">
                        <div class="floatRight questionPanel">
                                <?php echo $this->Form->create('Search',array('url'=>'index')); ?>
                                        
                                        <?php echo $this->Html->link('Post a Job', 'post', array('class' => 'btn btn-success strong')); ?>
                                        <?php echo $this->Html->link('Newest Jobs', 'index/newest'); ?> | 
                                        <?php echo $this->Html->link('Popular Jobs', 'index/popular'); ?> | 
                                        <?php echo $this->Form->input('keywords',array('label'=>false,'div'=>false,'size'=>5,'class'=>'searchBox','placeholder'=>'Search')); ?>
                                        <?php echo $this->Form->end(); ?>
                                </div>
                        <h1 class="questionH1">Jobs
                                
                        </h1>
                        <div class="clear"></div>
                        <?php if (!$jobs) { ?>
                                <div class="alert alert-danger">
                                        Unfortunately there are no Jobs.
                                </div>
                        <?php } else {
                                ?>
                                
                                <div class="postedQuestions">
                                        <?php
                                        foreach ($jobs as $job) {
                                                $jobId = $job['Job']['id'];
                                                ?>
                                                <div class="postedQuestion">

                                                        <span class="questionTitle"><?php echo $this->Html->link($job['Job']['name'], "viewJob/{$job['Job']['id']}/{$job['Job']['slug']}"); ?></span>
                                                        <?php echo $this->Html->link($this->Jv->shortenText($job['Job']['description']),"viewJob/{$job['Job']['id']}/{$job['Job']['slug']}",array('escape'=>false,'class' => 'textLikeLink')); ?>
                                                        <div class="clear"></div>
                                                        <span class="badge badge-info floatRight">
                                                                posted by <?php echo $this->element('user/basic', array('user' => $job['User'], 'noPhoto' => true)); ?> </span>
                                                        <span class="badge floatRight">
                                                        <?php echo $this->Jv->longTime($job['Job']['created']); ?>        
                                                        </span>
                                                        <div class="clear"></div>

                                                </div>

                                        <?php } ?>
                                </div>

                        <?php } ?>


                        <?php echo $this->element('paginator'); ?>

                </div>
        </div>
    
        <div class="span3">
                <div class="bordered minH600">
                        <h4>Latest Jobs</h4>
			 <?php if (!$latest_jobs) { ?>
                                <div class="alert alert-danger">
                                        Unfortunately there are no Jobs.
                                </div>
                        <?php } else {?>
                        <?php
                        foreach ($latest_jobs as $latest_job) {
                        	$latest_jobId = $latest_job['Job']['id'];
                        ?>
                        <div class="postedQuestion">

                        <span class="questionTitle"><?php echo $this->Html->link($latest_job['Job']['name'], "viewJob/{$latest_job['Job']['id']}/{$latest_job['Job']['slug']}"); ?></span>
                                                        <?php echo $this->Html->link($this->Jv->shortenText($latest_job['Job']['description']),"viewJob/{$latest_job['Job']['id']}/{$latest_job['Job']['slug']}",array('escape'=>false,'class' => 'textLikeLink')); ?>
							 <span class="threadLink"><?php echo $this->Html->link('More', "viewJob/{$latest_job['Job']['id']}/{$latest_job['Job']['slug']}"); ?></span>
                                                        <div class="clear"></div>       
                                                        </span>
                                                        <div class="clear"></div>

                                                </div>

                    <?php }//end for
		   } //end else
		   ?>
                </div>
        </div>
   
</div>
