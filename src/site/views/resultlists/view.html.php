<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');


class VWKViewResultLists extends JView
{
   // Overwriting JView display method
   function display($var_Template = null)
   {
      //get model object
      $id = JRequest::getInt('mid');
      $model =& $this->getModel('DataBase');

      //get match
      $match = $model->getMatch($id); //get "addressed" match details
      $this->match = $match;
      if ($match != null)
      {
         //get "match results"
         $matchResults = $model->getMatchResults($id);
         $this->matchResults = $matchResults; //assign variable to view

         //add breadcrumb
         $application =& JFactory::getApplication();
         $pathway = $application->getPathway();
         $pathway->addItem($match->name, '');
      }
      else
      {
         //todo 404
         echo 'Error 404';
      }

      //display view
      parent::display($var_Template);
   }
}
