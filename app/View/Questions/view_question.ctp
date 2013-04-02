<?php
/**
  Filename: view_question.ctp
  @author: Femi TAIWO [dftaiwo@gmail.com]
  Created: Feb 14, 2013  6:42:47 AM
 */
$isAnswerable = ($question['Question']['flag'] == 0);
//echo $_thisUserId;
//echo '<br />';
//echo $question['Question']['user_id'];
?>
<div class="row">
      <div class="span9">
            <div class="bordered">
                  <h1 class="questionTitle">
                        <?php echo $question['Question']['name']; ?>
                  </h1>
                  <div class="questionQuestion">
                        <?php
                        $fullDescription = $question['Question']['description'];

                        echo $this->Qv->highlight($fullDescription);
                        ?>
                  </div>


                  <div class="floatRight posterInfo">
                        <?php echo $this->element('user/basic', array('user' => $question['User'])); ?>
                        <br />Asked @ <?php echo $this->Qv->longTime($question['Question']['created']); ?>
                  </div>
                  <hr />
                  <div class="tags">
                        Tags: <?php foreach ($questionTags as $tag) { ?>
                              <span class="badge badge-success">
                                    <?php echo $this->Html->link($tag['Tag']['name'], "index/tag:{$tag['Tag']['id']}"); ?>
                              </span>
                        <?php } ?>
                  </div>
                  <div class="clear"></div>
                  <?php echo $this->element('questions/comments', array('comments' => $directComments)); ?>
                  <div class="clear"></div>

                  &nbsp;

                  <div class="alignRight">
                        <?php echo $this->Html->link('Share', "#", array('class' => 'btn btn-mini')); ?>
                        <?php
                        if ($isAnswerable) {
                              ?>
                              |   <?php
                              if ($_userInfo) {
                                    echo $this->Html->link('Post Comment', "#", array('class' => 'btn btn-mini btn-primary', 'data-toggle' => 'modal', 'data-target' => '#myCommentModal', 'data-remote' => $this->Html->url("postComment/$questionId/$questionSlug")));
                                    echo ' | ';
                                    echo $this->Html->link('Post Answer', "#postAnswer", array('class' => 'btn btn-mini btn-success'));
                              } else {
                                    echo $this->Html->link('Post Comment', "postComment/$questionId/$questionSlug", array('class' => 'btn btn-mini btn-primary'));
                                    echo ' | ';
                                    echo $this->Html->link('Post Answer', "postResponse/$questionId/$questionSlug", array('class' => 'btn btn-mini btn-success'));
                              }
                              ?>
                        </div>
                  <?php } ?>
            </div>
            <div class="postedAnswers">
                  <h4 class="answersHeader">Answers</h4>
                  <?php
                  if ($postedAnswers) {
                        ?>
                        <div class="">
                              <?php
                              foreach ($postedAnswers as $postedAnswer) {
                                    $answerId = $postedAnswer['QuestionComment']['id'];
                                    ?>
                                    <a name="<?php echo md5($answerId); ?>"></a>
                                    <div class="postedAnswer bordered">
                                          <div class="votingOptions">
                                                <?php
                                                echo $this->Html->link('Vote Up', "voteForAnswer/$questionId/$questionSlug/$answerId/1", array('class' => 'block', 'title' => 'This answer is  useful', 'class' => 'block', 'data-toggle' => 'tooltip'));

                                                echo '<span class="answerVoteCount">' . ($postedAnswer['QuestionComment']['vote_ups'] - $postedAnswer['QuestionComment']['vote_downs']) . '</span>';

                                                echo $this->Html->link('Vote Down', "voteForAnswer/$questionId/$questionSlug/$answerId/0", array('title' => 'This answer is not useful', 'class' => 'block', 'data-toggle' => 'tooltip'));
                                                if ($isAnswerable) {
                                                      if ($_thisUserId == $question['Question']['user_id']) {
                                                            echo '<hr />';
                                                            echo $this->Html->link('Choose as Best Answer', "chooseAnswer/{$questionId}/{$questionSlug}/$answerId", array('class' => 'btn btn-mini btn-success', 'data-toggle' => 'tooltip', 'title' => 'As the owner of this question, you can choose which is the best clue or solution among the ones submitted'));
                                                      }
                                                } else {
                                                      if ($postedAnswer['QuestionComment']['accepted_answer']) {
                                                            ?>
                                                            <?php echo $this->Html->image('tick.png'); ?>
                                                            <?php
                                                      }
                                                }
                                                ?>
                                          </div>
                                          <?php
//                                                        echo $postedAnswer['QuestionComment']['description']; 
                                          echo $this->Qv->highlight($postedAnswer['QuestionComment']['description']);
                                          ?>
                                          <div class="floatRight posterInfo">
                                                <?php echo $this->element('user/basic', array('user' => $postedAnswer['User'])); ?>
                                                <br />Asked @ <?php echo $this->Qv->longTime($postedAnswer['QuestionComment']['created']); ?>
                                          </div>
                                          <hr />

                                          <div class="clear"></div>
                                          <?php
                                          if (isset($postedComments[$answerId])) {
                                                echo $this->element('questions/comments', array('comments' => $postedComments[$answerId]));
                                          }
                                          ?>
                                          <?php if ($isAnswerable) { ?>
                                                &nbsp;

                                                <div class="alignRight">


                                                      <?php
                                                      if ($_userInfo) {
                                                            echo $this->Html->link('Post Comment', "#", array('class' => 'btn btn-mini btn-primary', 'data-toggle' => 'modal', 'data-target' => '#myCommentModal', 'data-remote' => $this->Html->url("postComment/$questionId/$questionSlug/$answerId")));
                                                      } else {
                                                            echo $this->Html->link('Post Comment', "postComment/$questionId/$questionSlug/$answerId", array('class' => 'btn btn-mini btn-primary'));
                                                      }
                                                      ?> | 
                                                      <?php echo $this->Html->link('Post Answer', '#postAnswer', array('class' => 'btn btn-mini btn-success')); ?>
                                                </div>
                                          <?php } ?>
                                    </div>
                              <?php } ?>

                        </div>
                  <?php } elseif ($isAnswerable) { ?>
                        <div class="alert alert-info">
                              There are currently no answers posted. 
                        </div>
                  <?php } else { ?>
                        There are currently no answers posted and this question can no longer be answered
                  <?php } ?>
            </div>
            <?php if ($isAnswerable) { //still open for replies    ?>
                  <a name="postAnswer"></a>
                  <?php
                  if ($_userInfo) {
                        echo $this->element('questions/response_form');
                  } else {
                        ?>
                        <div class="alert alert-info">
                              To post your answer, please log-in first
                              <?php echo $this->Html->link('Log In/Sign Up', "postResponse/$questionId/$questionSlug", array('class' => 'btn')); ?>
                        </div>      
                  <?php } ?>
            <?php } else { ?>
                  <div class="alert alert-info">
                        This question is no longer open for answers.
                  </div>
            <?php } ?>
      </div>
</div>
<div class="modal hide custom-width-modal" id="myCommentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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


<?php echo $this->element('syntax_highlighter'); ?>
