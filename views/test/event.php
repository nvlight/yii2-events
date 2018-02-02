<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 31.01.2018
 * Time: 23:18
 */

use app\components\Debug;
?>


<h4>category name: <?=$events[4]['category']->name?></h4>
<h4>type name: <?=$events[4]['type']?></h4>

<?php //echo Debug::d($events); ?>
<?php echo Debug::d($events[4]->types->name); ?>


