<?php $isLocalhost = ($_SERVER['HTTP_HOST'] == 'localhost'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

      <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <title>
                  <?php echo Configure::read('Application.name') ?>
                  <?php
                  if (isset($articleInfo)) {
                        echo ' - ' . $articleInfo['Article']['name'];
                  }
                  if (isset($question)) {
                        echo ' - ' . $question['Question']['name'];
                  }
                  ?></title>
            <meta name="description" content="">
            <meta name="viewport" content="width=device-width">

            <link href="https://plus.google.com/<?php echo Configure::read('Application.gplus_page_id'); ?>" rel="publisher" />
            <?php if (!$isLocalhost) { ?>
                  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
            <?php } ?>
            <script>window.jQuery || document.write('<script src="<?php echo $this->params->webroot ?>js/lib/jquery.min.js"><\/script>')</script>

            <?php
            $scriptsToLoad = array(
                'lib/bootstrap.min',
                'src/scripts.js',
            );
            if (isset($usesAutocomplete) && $usesAutocomplete) {
                  $scriptsToLoad[] = 'lib/jquery-ui';
            }
            echo $this->Html->script(
                    $scriptsToLoad);
            ?>

            <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
            <?php echo $this->Html->css('normalize.css') ?>
            <?php echo $this->Html->css('jquery-ui.css') ?>
            <?php echo $this->Html->css('bootstrap-' . Configure::read('Layout.theme') . '.min', null, array('data-extra' => 'theme')) ?>
            <?php echo $this->Html->css('bootstrap-responsive.min') ?>
            <?php echo $this->Html->css('font-awesome.min') ?>
            <?php echo $this->Html->css('style') ?>


            <?php
            if (is_file(WWW_ROOT . 'css' . DS . $this->params->controller . '.css')) {
                  echo $this->Html->css($this->params->controller);
            }
            if (is_file(WWW_ROOT . 'css' . DS . $this->params->controller . DS . $this->params->action . '.css')) {
                  echo $this->Html->css($this->params->controller . '/' . $this->params->action);
            }
            ?>


            <?php echo $this->Html->script('lib/modernizr') ?>

	    <?php //widget files for date and time pickers in forms
		 
		  echo $this->Html->css('zebra_datepicker');
		  echo $this->Html->css('timePicker');
 
		 echo $this->Html->script('jquery.timePicker.min');
		 echo $this->Html->script('zebra_datepicker');
	   ?>
      </head>
      <body>
            <!--[if lt IE 7]>
                <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
                <![endif]-->

            <div class="navbar navbar-static">
                  <div class="navbar-inner">
                        <div class="container">
                              <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                              </a>

                              <?php echo $this->Html->link(Configure::read('Application.name'), "/", array('class' => 'brand', 'escape' => false)) ?>

                              <div class="nav-collapse">
                                    <ul class="nav">
                                          <li class="topPlusOne">
                                          <g:plusone></g:plusone>
                                          </li>
                                          <li>
                                                <?php echo $this->Html->link('Want to Help?', "/Dashboard/help"); ?>
                                          </li>
                                          <li>
                                                <?php echo $this->Html->link('Blog', "/Dashboard/help"); ?>
                                          </li>
                                          <li>
                                                <?php echo $this->Html->link('Resources', "/Resources/index"); ?>
                                          </li>
                                          <li>
                                                <?php echo $this->Html->link('Jobs', "/Jobs/index"); ?>
                                          </li>
                                          <li>
                                                <?php echo $this->Html->link('Events', "/Events/index"); ?>
                                          </li>
                                          <li>
                                                <?php echo $this->Html->link('Technology', "/Dashboard/help"); ?>
                                          </li>
                                          <li class="dropdown">
                                                <?php echo $this->Html->link('I Want To', "#", array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown')); ?>
                                                <ul class="dropdown-menu ucwords" role="menu" aria-labelledby="dLabel">
                                                      <li><?php echo $this->Html->link('ask a question', '/Questions/ask'); ?></li>
                                                      <li><?php echo $this->Html->link('contribute answers', '/Questions'); ?></li>
                                                      <li><?php echo $this->Html->link('contribute code', '#'); ?></li>

                                                      <li><?php echo $this->Html->link('learn how to design', '#'); ?></li>
                                                      <li><?php echo $this->Html->link('learn how to build mobile apps', '#'); ?></li>
                                                      <li><?php echo $this->Html->link('learn how to build websites', '#'); ?></li>
                                                </ul>
                                          </li>
                                    </ul>

                                    <ul class="nav pull-left">
                                          <li id="fat-menu" class="dropdown">
                                                <a href="#" id="drop3" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                                      <i class="icon-black icon-user"></i>Member Center<b class="caret"></b></a>
                                                <ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
                                                      <li>
                                                            <?php
                                                            echo $this->Html->link(
                                                                    '<i class="icon-black icon-fire"></i>Forum', '#', array(
                                                                'tabindex' => '-1',
                                                                'escape' => false
                                                                    )
                                                            )
                                                            ?>
                                                      </li>
                                                      <li>
                                                            <?php
                                                            echo $this->Html->link(
                                                                    '<i class="icon-black icon-bullhorn"></i>Events', '#', array(
                                                                'tabindex' => '-1',
                                                                'escape' => false
                                                                    )
                                                            )
                                                            ?>
                                                      </li>
                                                </ul>
                                          </li>
                                    </ul>  
                                    <?php if (isset($_userInfo) && $_userInfo) { ?>
                                          <ul class="nav pull-right">
                                                <li id="fat-menu" class="dropdown">
                                                      <a href="#" id="drop3" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-black icon-user"></i> 
                                                            <?php echo $_userInfo['User']['name'] ?> <b class="caret"></b></a>
                                                      <ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
                                                            <li>
                                                                  <?php
                                                                  echo $this->Html->link(
                                                                          '<i class="icon-black icon-suitcase"></i>My Account', '/users/user_profile/', //i don't a clean way of fetching just the logged in user....moh
                                                                          array(
                                                                      'tabindex' => '-1',
                                                                      'escape' => false
                                                                          )
                                                                  );
                                                                  echo $this->Html->link(
                                                                          '<i class="icon-black icon-suitcase"></i>My Work Profile', '/MyProfile/', //i don't a clean way of fetching just the logged in user....moh
                                                                          array(
                                                                      'tabindex' => '-1',
                                                                      'escape' => false
                                                                          )
                                                                  );
                                                                  ?>
                                                            </li>
                                                            <li>
                                                                  <?php
                                                                  echo $this->Html->link(
                                                                          '<i class="icon-black icon-off"></i> Logout', '/users/logout', array(
                                                                      'tabindex' => '-1',
                                                                      'escape' => false
                                                                          )
                                                                  );
                                                                  ?>

                                                            </li>
                                                      </ul>
                                                </li>
                                          </ul>   
                                    <?php } ?>
                                    </li>
                                    </ul>
                                    </li>
                                    </ul>   


                              </div><!--/.nav-collapse -->
                        </div>
                  </div>
            </div>

            <div class="container" >
                  <div role="main" id="main">
                        <?php
                        echo $this->Session->flash();
                        ?>
                        <?php echo $this->fetch('content'); ?>
                  </div>
                  <hr>

                  <footer>
                        <p><?php echo Configure::read('Application.name') ?> </p>
                  </footer>
<?php echo $this->element('sql_dump'); ?>
            </div> <!-- /container -->


<?php
if (is_file(WWW_ROOT . 'js' . DS . $this->params->controller . '.js')) {
      echo $this->Html->script($this->params->controller);
}
if (is_file(WWW_ROOT . 'js' . DS . $this->params->controller . DS . $this->params->action . '.js')) {
      echo $this->Html->script($this->params->controller . '/' . $this->params->action);
}
?>



            <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<!--                      <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
              g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
              s.parentNode.insertBefore(g,s)}(document,'script'));
            </script>
            -->
<?php if (!$isLocalhost) { ?>

                  <script type="text/javascript">
                                (function() {
                                      var po = document.createElement('script');
                                      po.type = 'text/javascript';
                                      po.async = true;
                                      po.src = 'https://apis.google.com/js/plusone.js';
                                      var s = document.getElementsByTagName('script')[0];
                                      s.parentNode.insertBefore(po, s);
                                })();
                  </script>
                  <script>!function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (!d.getElementById(id)) {
                                    js = d.createElement(s);
                                    js.id = id;
                                    js.src = "https://platform.twitter.com/widgets.js";
                                    fjs.parentNode.insertBefore(js, fjs);
                              }
                        }(document, "script", "twitter-wjs");</script>
<?php } ?>
      </body>
</html>
