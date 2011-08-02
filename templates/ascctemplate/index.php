<?php defined('_JEXEC') or die('Restricted access'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
      xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
    <head>

        <jdoc:include type="head" />
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/styles.css" type="text/css" />

    </head>

    <body>
        <div class="container">

            <div class="header"> 

                <div class="menu">

                    <jdoc:include type="modules" name="menu" />

                </div> <?php // End menu ?>

            </div> <?php // End header ?>

            <div class="component">

                <jdoc:include type="component" />

            </div> <?php // End component ?>

            <div class="footer">

                <jdoc:include type="modules" name="footer" />

            </div> <?php // End footer ?>

        </div> <?php // End container ?>
    </body>
</html>