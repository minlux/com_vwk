<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<form action="<?php echo JRoute::_('index.php?option=com_vwk'); ?>" method="post" name="adminForm">
   <table>
      <tr>
         <td>Name</td>
         <td><input class="text_area" type="text" name="name" value="<?php echo $this->match->name; ?>" /></td>
      </tr>
   </table>
   <div>
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="view" value="matchedit" />
      <input type="hidden" name="mid" value="<?php echo $this->match->id; ?>" />
   </div>
</form>
