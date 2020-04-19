<?php
//no direct access to this file
defined('_JEXEC') or die('Restricted access');
//import "helper class" to do XSLT transformation
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'utilities'.DS.'xsltransform.php');
//add custom stylesheet
JHtml::stylesheet('components/com_vwk/views/style/style.css');
?>
<?php
   //xml DOM
   $xmlDOM = new DomDocument();
   $xmlDOM->formatOutput = true;
   //xslt processor
   $xsltProc = new C_XslTransform();
   $xslFile = JPATH_COMPONENT_SITE.DS.'views'.DS.'resultlists'.DS.'tmpl'.DS.'xml2html.xsl';

   echo '<h1>' . $this->match->name . '</h1>';
   foreach ($this->matchResults as $idx => $result)
   {
      //set anchor and headline
      echo '<div class="vwklist">';
      echo '<h2>' . $result->name . '</h2>';

      //do transformation
      $xmlDOM->loadXML($result->xml);
      $xmlString = $xsltProc->transformToXML($xmlDOM, $xslFile);
      //print ergebnisse
      $xmlString = substr($xmlString, 143); //strip XHTML header
      echo $xmlString;

      echo '<span class="vwkupdate">Update: ' . $result->updatetime . '</span>';
      echo '</div>';
   }

   //print "back link"
   $link = JRoute::_('index.php?option=com_vwk&view=matchlist');
   print '<a href="'.$link.'">&lt; Ergebnisse</a>';
?>
