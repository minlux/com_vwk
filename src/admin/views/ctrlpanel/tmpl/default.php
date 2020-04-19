<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
//add custom stylesheet
JHtml::stylesheet('administrator/components/com_vwk/views/style/style.css');
?>

<div id="vwk">
<form action="<?php echo JRoute::_('index.php?option=com_vwk'); ?>" method="post" name="adminForm">
   <table class="adminlist">
      <thead>
         <tr>
            <th class="chkbox">
               <?php
                  $count = 0;
                  foreach($this->matchResultTree as &$match)
                  {
                     $count++;
                     $count += count($match->result);
                  }
               ?>
               <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $count; ?>);" />
            </th>
            <th class="name">
               <?php echo JText::_('Name'); ?>
            </th>
            <th class="publish">
               <?php echo JText::_('Publish'); ?>
            </th>
            <th class="order">
               <?php echo JText::_('Order'); ?>
            </th>
         </tr>
      </thead>
      <tbody>
         <?php $count = 0; ?>
         <?php foreach($this->matchResultTree as &$match): ?>
            <tr class="match <?php echo 'row' . ($count % 2); ?>">
               <td class="chkbox">
                  <?php echo JHtml::_('grid.id', $count, $match->id, false, 'mid'); ?>
               </td>
               <td class="name">
                  <a href="<?php echo JRoute::_('index.php?option=com_vwk&task=edit&mid[]=' . $match->id); ?>">
                     <?php echo $match->name; ?>
                  </a>
               </td>
               <td class="publish">
                  <?php echo JHtml::_('jgrid.published', $match->publish, $count); ?>
               </td>
               <td class="order">
               <?php
                  echo JHtml::_('jgrid.orderup', $count, 'orderup');
                  echo JHtml::_('jgrid.orderdown', $count, 'orderdown');
               ?>
               </td>
            </tr>
            <?php $count++; ?>
            <?php foreach($match->result as &$result): ?>
            <tr class="result <?php echo 'row' . ($count % 2); ?>">
               <td class="chkbox">
                  <?php echo JHtml::_('grid.id', $count, $result->id, false, 'rid'); ?>
               </td>
               <td class="name">
                  <a href="<?php echo JRoute::_('index.php?option=com_vwk&task=edit&rid[]=' . $result->id); ?>">
                     <?php echo $result->name; ?>
                  </a>
               </td>
               </td>
               <td class="publish">
                  <?php echo JHtml::_('jgrid.published', $result->publish, $count); ?>
               </td>
               <td class="order">
               <?php
                  echo JHtml::_('jgrid.orderup', $count, 'orderup');
                  echo JHtml::_('jgrid.orderdown', $count, 'orderdown');
               ?>
            </tr>
            <?php $count++; ?>
            <?php endforeach; ?>
         <?php endforeach; ?>
      </tbody>
   </table>
   <div>
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="boxchecked" value="0" />
      <?php echo JHtml::_('form.token'); ?>
   </div>
</form>
</div>
