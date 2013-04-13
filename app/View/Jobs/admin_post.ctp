<?php
/**
  Filename: post.ctp
  @author: Damilare Fagbemi [damilarefagbemi@gmail.com]
  Created: Mar 22, 2013  11:43:00 AM
 */
?>
<?php echo $this->element('breadcrumb'); ?> 
<div>

      <h1>Post A Job</h1>
      <p>
            This portion should explain the rules and provide tips for posting jobs.
      </p>
      <div class="row">
            <div class="span7 bordered shadowed fullWidth">        

                  <?php echo $this->Form->create('Post'); ?>
                  <?php echo $this->element('form_validator'); ?>
                  <?php echo $this->Form->input('title'); ?>

                  <?php echo $this->Form->input('description', array('type' => 'textarea', 'rows' => 10, 'required')); ?>
              <div class="fullWidth">
<?php echo $this->Form->input('selSkills',array('id'=>'selSkills','label'=>'Enter Required Skills Below','required')); ?>
              </div>
               <span id="searchLog"></span>
                  <?php echo $this->Form->input('registration_link', array('label'=>'Registration Link <em> &nbsp;(fully qualified url)</em>')); ?>
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
 <div class="span4">

                        
                <div class="bordered minH600">
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
