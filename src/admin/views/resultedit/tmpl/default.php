<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

//import "helper class" to do XSLT transformation
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'utilities'.DS.'xsltransform.php');
//add custom stylesheet
JHtml::stylesheet('components/com_vwk/views/style/resultlists.css');
?>

<form action="<?php echo JRoute::_('index.php?option=com_vwk'); ?>" method="post" enctype="multipart/form-data" name="adminForm">
   <table>
      <tr>
         <td>Parent Match</td>
         <td>
            <select name="match">
            <?php
               foreach ($this->matchList as $match)
               {
                  $selected = '';
                  if ($match->id == $this->result->matchid)
                  {
                     $selected = ' selected="selected"';
                  }
                  echo '<option value="' . $match->id . '"' . $selected . '>' . $match->name . '</option>';
               }
            ?>
            </select>
         </td>
      </tr>
      <tr>
         <td>Name</td>
         <td><input class="text_area" type="text" name="name" value="<?php echo $this->result->name; ?>" /></td>
      </tr>
      <tr>
         <td>WM-Shot XML</td>
         <td><input type="file" name="file"></td>
      </tr>
   </table>
   <?php
      $xsltProc = new C_XslTransform();
      $xslFile = JPATH_COMPONENT_SITE.DS.'views'.DS.'resultlists'.DS.'tmpl'.DS.'xml2html.xsl';

      //load xml file into DOM
      $xmlDOM = new DomDocument();
      $xmlDOM->formatOutput = true;
      $xmlDOM->loadXML($this->result->xml);

      //do transformation
      $xmlString = $xsltProc->transformToXML($xmlDOM, $xslFile);

      //print ergebnisse
      $xmlString = substr($xmlString, 143); //strip XHTML header
      echo $xmlString;
   ?>
   <span class="vwkupdate"><?php echo 'Update: ' . $this->result->updatetime; ?></span>
   <div>
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="view" value="resultedit" />
      <input type="hidden" name="rid" value="<?php echo $this->result->id; ?>" />
      <?php echo JHtml::_('form.token'); ?>
   </div>
</form>
