<?php
/**
  Filename: admin_edit.ctp
  @author: Damilare Fagbemi [damilarefagbemi@gmail.com]
  Created: Mar 24, 2013  08:43:00 AM
 */
?>
<?php echo $this->element('breadcrumb'); ?> 
<div>

      <h1>Edit Event</h1>
      <div class="row">
            <div class="span7 bordered shadowed fullWidth">        
                  <?php date_default_timezone_set('Africa/Lagos'); ?>
                  <?php echo $this->Form->create('Edit'); ?>
                  <?php echo $this->element('form_validator'); ?>
                  <?php echo $this->Form->input('name', array('default' => $event['Event']['name'])); ?>
  		  <?php echo $this->Form->input('description', array('type' => 'textarea', 'default' => $event['Event']['description'], 'rows' => 10, 'required')); ?>
 		  <?php echo $this->Form->input('venue', array('default' => $event['Event']['venue'])); ?>
		 <?echo $this->Form->input('start', array('type' => 'text', 'label' => 'Starts', 'default' => $event['Event']['start'])); ?>
		 <?echo $this->Form->input('end', array('type' => 'text', 'label' => 'Ends', 'default' => $event['Event']['end'])); ?>

                <?php echo $this->Form->input('registration_link', array('default' => $event['Event']['registration_link'],'label'=>'Registration Link <em> &nbsp;(fully qualified url)</em>')); ?>
		<span id="searchLog"></span>
		<!--
                <div id="selectedSkills" class="userSkills">
                      <div class="userSkills">
                        <?php
                        foreach ($mySkillSets as $skillRow) {
                              ?><span>&Because;<?php echo $skillRow['Skillset']['name']; ?>
                              <?php echo $this->Html->link('[x]',"removeSkill/{$skillRow['Skillset']['id']}"); ?>
                              </span>
                                    <?php
                              }
                              ?></div>

                        <div class="clear"></div>
                        <p>&nbsp;</p>
                       
                </div>

		-->
<!--
                  <div class="ui-widget">
                <?php echo $this->Form->input('tags', array('id' => 'tags', 'label' => 'Tags <span class="smaller">Used to identity technologies, platforms and products. Separate different tags using commas</span>')); ?> 
        </div>
-->
                  <?php echo $this->Form->submit('Submit'); ?>
                  <?php echo $this->Form->end(); ?>
            </div>
            <div class="span4 ">

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
      </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
 
    //set default dates with time

    var def_start = $("#EditStart").val(); //gets the default date and time value set in the start field
    var def_start_arr = def_start.split(" ");

    var def_start_date = def_start_arr[0];
    var def_start_time = def_start_arr[1];

    var def_end = $("#EditEnd").val(); //gets the default date and time value set in the start field
    var def_end_arr = $("#EditEnd").val().split(" ");

    var def_end_date = def_end_arr[0];
    var def_end_time = def_end_arr[1];


    //hide start field and add two inputs in its place for date + time
    //when submitted, put their values into start
    $('#EditStart').hide().after('<input type="text" name="StartDate" id="StartDate" value=' + def_start +' style="width:200px" >&nbsp;<input type="text" name="StartTime" id="StartTime" value=' + def_start_time +  ' style="width:200px" readonly>');
    //enable datepicker
    $("#StartDate").Zebra_DatePicker({
      format: 'Y-m-d',
      direction: true ,
    });
    //enable timepicker
    $("#StartTime").timePicker({
      startTime: "06:00",
      endTime: "23:00",
      show24Hours: true,
      separator: ':',
      step: 20
    });
    
    //hide end field and add two inputs in its place for date + time
    //when submitted, put their values into start
    $('#EditEnd').hide().after('<input type="text" name="EndDate" id="EndDate" value=' + def_end_date +' style="width:200px" >&nbsp;<input type="text" name="EndTime" id="EndTime" value=' + def_end_time + ' style="width:200px" readonly>');
    //enable datepicker
    $("#EndDate").Zebra_DatePicker({
      format: 'Y-m-d',
      direction: true 
    });
    //enable timepicker
    $("#EndTime").timePicker({
      startTime: "06:00",
      endTime: "23:00",
      show24Hours: true,
      separator: ':',
      step: 20
    });
 
 
    //put values back into CakePHP input element
    $("#EditAdminEditForm").submit(function() {
      var StartDate = $("#StartDate").val();
      var StartTime = $("#StartTime").val();
      StartDate = StartDate.split('-');
      $("#EditStart").val(StartDate[0] + '-' + StartDate[1] + '-' + StartDate[2] + ' ' + StartTime);

      var EndDate = $("#EndDate").val();
      var EndTime = $("#EndTime").val();
      EndDate = EndDate.split('-');
      $("#EditEnd").val(EndDate[0] + '-' + EndDate[1] + '-' + EndDate[2] + ' ' + EndTime);
    });

  });
</script>
