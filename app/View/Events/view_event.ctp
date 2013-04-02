<?php
/**
  Filename: view_event.ctp
  @author: Damilare Fagbemi [damilarefagbemi@gmail.com]
  Created: Mar 22, 2013  8:42:47 AM

*/
?>
<?php echo $this->element('breadcrumb'); ?> 
<div class="row">
      <div class="span9">
            <div class="bordered">
                  <h1 class="questionTitle">
                        <?php echo $event['Event']['name']; ?>
                  </h1>
                  <div class="questionQuestion">
                        <?php
                        $fullDescription = $event['Event']['description'];

                        echo $this->Ev->highlight($fullDescription);
                        ?>
                  </div>

		<div class="floatRight posterInfo">
                        <?php echo $this->element('user/basic', array('user' => $event['User'])); ?>
                        <br />Posted @ <?php echo $this->Ev->longTime($event['Event']['created']); ?>
                  </div>
                 <div><br />
		<strong>Venue:</strong>  <?php echo $event['Event']['venue']; ?><br />
		<strong>Starts:</strong>  &nbsp;<?php echo $this->Ev->longTime($event['Event']['start']); ?><br />
		<strong>Ends:</strong> &nbsp;&nbsp;<?php echo $this->Ev->longTime($event['Event']['end']); ?>
		</div>
               <?php if($event['Event']['registration_link'] != "") { 
		 echo "<div><br /><a href =". $event['Event']['registration_link'] ." target='_blank'>Click here to register</a></div>";
                  } ?>
                  <hr />

	</div>
      </div>
        <div class="span3">
                <div class="bordered ">
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
</div>
