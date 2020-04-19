<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//add custom stylesheet
JHtml::stylesheet('components/com_vwk/views/style/style.css');
?>

<div id="vwkmatchlist">
<h2>Wettkampf Liste</h2>
<ul>
<?php
   foreach($this->matchList as $match)
   {
      echo '<div class="match">';
      $link = JRoute::_('index.php?option=com_vwk&view=resultlists&mid=' . $match->id);
      print '<li><a href="'.$link.'">' . $match->name . '</a></li>';
      echo '</div>';
   }
?>
</ul>
</div>

