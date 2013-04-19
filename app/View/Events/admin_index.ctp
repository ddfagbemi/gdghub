<?php

/**
 * Filename: admin_edit.ctp
 * @author: Damilare Fagbemi [damilarefagbemi@gmail.com]
 * Created: Mar 24, 2013  08:31:08 AM 
 */

require('googlecalendar_classes/date.class.php');
require('googlecalendar_classes/Dates.class.php');
require('googlecalendar_classes/GoogleCalendar.class.php');

?>
<?php echo $this->element('breadcrumb'); ?> 
<div class="row">
        <div class="span9">
                <div class="bordered minH600">
                        <div class="floatRight questionPanel">
                                <?php echo $this->Form->create('Search',array('url'=>'index')); ?>
                                        
                                        <?php echo $this->Html->link('Submit an Event', 'post', array('class' => 'btn btn-success strong')); ?>
                                        <?php echo $this->Html->link('Newest Events', 'index/newest'); ?> | 
                                        <?php echo $this->Html->link('Popular Events', 'index/popular'); ?> | 
                                        <?php echo $this->Form->input('keywords',array('label'=>false,'div'=>false,'size'=>5,'class'=>'searchBox','placeholder'=>'Search')); ?>
                                        <?php echo $this->Form->end(); ?>
                                </div>
                        <h1 class="questionH1">Events
                                
                        </h1>
                        <div class="clear"></div>
                        <?php if (!$events) { ?>
                                <div class="alert alert-danger">
                                        Unfortunately there are no events.
                                </div>
                        <?php } else {
                                ?>
                           
                                <div class="postedQuestions">
                                        <?php
                                        foreach ($events as $event) {
                                                $eventId = $event['Event']['id'];
						$published = $event['Event']['published'];
                                                ?>
                                                <div class="postedQuestion">

                                                        <span class="questionTitle"><?php echo $this->Html->link($event['Event']['name'], "viewEvent/{$event['Event']['id']}/{$event['Event']['slug']}"); ?></span>
                                                        <?php echo $this->Html->link($this->Ev->shortenText($event['Event']['description']),"viewEvent/{$event['Event']['id']}/{$event['Event']['slug']}",array('escape'=>false,'class' => 'textLikeLink')); ?>
                                                        <br /><div class="clear"></div>
<span class="floatLeft">
                        <?php   
				$publish_btn = "";	
				if($published) 
				{$publish_btn = "Unpublish";}
                  		else{ $publish_btn = "Publish";}
				echo $this->Html->link($publish_btn, "#", array('class' => 'btn btn-mini btn-success', 'data-toggle' => 'modal', 'data-target' => '#publishModal', 'data-remote' => $this->Html->url("publish/$eventId/{$event['Event']['slug']}")));
				echo ' ';
				/*echo $this->Html->link('Edit', "#", array('class' => 'btn btn-mini btn-success', 'data-toggle' => 'modal', 'data-target' => '#editModal', 'data-remote' => $this->Html->url("edit/{$event['Event']['id']}/{$event['Event']['slug']}")));*/
				echo $this->Html->link('Edit', "edit/{$event['Event']['id']}/{$event['Event']['slug']}", array('class' => 'btn btn-mini btn-primary'));
				echo ' | ';
                                echo $this->Html->link('Delete', "#", array('class' => 'btn btn-mini btn-danger', 'data-toggle' => 'modal', 'data-target' => '#deleteModal', 'data-remote' => $this->Html->url("delete/{$event['Event']['id']}/{$event['Event']['slug']}")));
                              ?>
                        </span>
                                                        <span class="badge badge-info floatRight">
                                                                submitted by <?php echo $this->element('user/basic', array('user' => $event['User'], 'noPhoto' => true)); ?> </span>
                                                        <span class="badge floatRight">
                                                        <?php echo $this->Ev->longTime($event['Event']['created']); ?>        
                                                        </span>
							<span class="floatRight">
							<?php

							//converting mysql datetime into googlecalendar's expected time format
							//"2012-12-31 20:00:00" to "20121231T200000Z"
							$start_date = $event['Event']['start'];
							$start_date = str_replace("-","",$start_date);
							$start_date = str_replace(":","",$start_date);
							$start_date = str_replace(" ","T",$start_date);
							$start_date = $start_date."Z";

							$end_date = $event['Event']['end'];
							$end_date = str_replace("-","",$end_date);
							$end_date = str_replace(":","",$end_date);
							$end_date = str_replace(" ","T",$end_date);
							$end_date = $end_date."Z";
							
							$params = array('title' => $event['Event']['name'],
								'datetime' => array('start' => $start_date, 'end' => $end_date),
								'location' => $event['Event']['venue'],
								'description' => $event['Event']['description']
							    );
							//$link = 
							$gCal = GoogleCalendar::createEventReminder($params);
							echo "$gCal"."&nbsp;";
							?> 
						        </span>
                                                        <div class="clear"></div>
                                                </div>

                                        <?php } ?>

                                </div>

                  
            </div>

                        <?php } ?>


                        <?php echo $this->element('paginator'); ?>

         </div>
         <div class="span3">
                <div class="bordered minH600">
                        <h4>Latest Events</h4>
			 <?php if (!$latest_events) { ?>
                                <div class="alert alert-danger">
                                        Unfortunately there are no Events.
                                </div>
                        <?php } else {?>
                        <?php
                        foreach ($latest_events as $latest_event) {
                        	$latest_eventId = $latest_event['Event']['id'];
                        ?>
                        <div class="postedQuestion">

                        <span class="questionTitle"><?php echo $this->Html->link($latest_event['Event']['name'], "viewEvent/{$latest_event['Event']['id']}/{$latest_event['Event']['slug']}"); ?></span>
                                                        <?php echo $this->Html->link($this->Ev->shortenText($latest_event['Event']['description']),"viewEvent/{$latest_event['Event']['id']}/{$latest_event['Event']['slug']}",array('escape'=>false,'class' => 'textLikeLink')); ?>
							 <span class="threadLink"><?php echo $this->Html->link('More', "viewEvent/{$latest_event['Event']['id']}/{$latest_event['Event']['slug']}"); ?></span>
                                                        <div class="clear"></div>       
                                                        </span>
                                                        <div class="clear"></div>

                                                </div>

                    <?php }//end for
		   } //end else
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
