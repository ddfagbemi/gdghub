<?php
/**
  Filename: admin_publish.ctp
  @author: Damilare Fagbemi [damilarefagbemi@gmail.com]
  Created: Mar 24, 2013  04:39:18 AM
 */
?>
<h5>Publish/ Unpublish Job : <span><?php echo $this->Html->link($job['Job']['name'],"viewJob/{$job['Job']['id']}/{$job['Job']['slug']}"); ?></span></h5>

<div class="answerForm bordered">
        <?php echo $this->Form->create('Publish'); ?>
        Published: <?php echo $this->Form->checkbox('published',array('default'=>$job['Job']['published'])); ?><br /><br />
        <?php echo $this->Form->submit('Update', array('class' => 'btn btn-success')); ?>
</div>


