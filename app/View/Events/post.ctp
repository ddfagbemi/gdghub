<?php
/**
  Filename: post.ctp
  @author: Damilare Fagbemi [damilarefagbemi@gmail.com]
  Created: Mar 22, 2013  11:43:00 AM
 */
?>
<?php echo $this->element('breadcrumb'); ?> 
<div>

      <h1>Submit an Event</h1>
      <div class="floatRight"><?php echo $this->Html->link('Browse Events', 'index'); ?></div>
      <p>
            This portion should explain the rules and provide tips for posting events.
      </p>
      <div class="row">
            <div class="span7 bordered shadowed fullWidth">        
                  <?php date_default_timezone_set('Africa/Lagos'); ?>
                  <?php echo $this->Form->create('Post'); ?>
                  <?php echo $this->element('form_validator'); ?>
                  <?php echo $this->Form->input('name'); ?>
  		  <?php echo $this->Form->input('description', array('type' => 'textarea', 'rows' => 10, 'required')); ?>
 		  <?php echo $this->Form->input('venue'); ?>
		 <?echo $this->Form->input('start', array('type' => 'text', 'label' => 'Starts', 'default' => date('Y-m-d H:i'))); ?>
		 <?echo $this->Form->input('end', array('type' => 'text', 'label' => 'Ends', 'default' => date('Y-m-d H:i'))); ?>
                  <?php echo $this->Form->input('registration_link', array('label'=>'Registration Link <em> &nbsp;(fully qualified url)</em>')); ?>
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


<script>
        $(function() {
                var availableTags = <?php echo json_encode($possibleSkills); ?>
                
                function split( val ) {
                        return val.split( /,\s*/ );
                }
                function extractLast( term ) {
                        return split( term ).pop();
                }
 
                $( "#selSkills" )
                // don't navigate away from the field on tab when selecting an item
                .bind( "keydown", function( event ) {
                        if ( event.keyCode === $.ui.keyCode.TAB &&
                                $( this ).data( "autocomplete" ).menu.active ) {
                                event.preventDefault();
                        }
                })
                .autocomplete({
                        minLength: 0,
                        source: function( request, response ) {
                                // delegate back to autocomplete, but extract the last term
                                response( $.ui.autocomplete.filter(
                                availableTags, extractLast( request.term ) ) );
                        },
                        focus: function() {
                                // prevent value inserted on focus
                                return false;
                        },
                        select: function( event, ui ) {
                                var terms = split( this.value );
                                // remove the current input
                                terms.pop();
                                // add the selected item
                                terms.push( ui.item.value );
                                // add placeholder to get the comma-and-space at the end
                                terms.push( "" );
                                this.value = terms.join( ", " );
                                return false;
                        }
                });
  $(window).keydown(function(event){
    if( (event.keyCode == 13)) {
      event.preventDefault();
      return false;
    }
  });
  
        });
</script>
<script type="text/javascript">
  $(document).ready(function(){
 
    //set default dates with time

    var def_start = $("#PostStart").val(); //gets the default date and time value set in the start field
    var def_start_arr = def_start.split(" ");

    var def_start_date = def_start_arr[0];
    var def_start_time = def_start_arr[1];

    var def_end = $("#PostEnd").val(); //gets the default date and time value set in the start field
    var def_end_arr = $("#PostEnd").val().split(" ");

    var def_end_date = def_end_arr[0];
    var def_end_time = def_end_arr[1];


    //hide start field and add two inputs in its place for date + time
    //when submitted, put their values into start
    $('#PostStart').hide().after('<input type="text" name="StartDate" id="StartDate" value=' + def_start +' style="width:200px" >&nbsp;<input type="text" name="StartTime" id="StartTime" value=' + def_start_time +  ' style="width:200px" readonly>');
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
    $('#PostEnd').hide().after('<input type="text" name="EndDate" id="EndDate" value=' + def_end_date +' style="width:200px" >&nbsp;<input type="text" name="EndTime" id="EndTime" value=' + def_end_time + ' style="width:200px" readonly>');
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
    $("#PostPostForm").submit(function() {
      var StartDate = $("#StartDate").val();
      var StartTime = $("#StartTime").val();
      StartDate = StartDate.split('-');
      $("#PostStart").val(StartDate[0] + '-' + StartDate[1] + '-' + StartDate[2] + ' ' + StartTime);

      var EndDate = $("#EndDate").val();
      var EndTime = $("#EndTime").val();
      EndDate = EndDate.split('-');
      $("#PostEnd").val(EndDate[0] + '-' + EndDate[1] + '-' + EndDate[2] + ' ' + EndTime);
    });

  });
</script>
