<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * VWK-TeamList View
 */
class VWKViewMatchList extends JView
{
   // Overwriting JView display method
   function display($template = null)
   {
      //get model object
      $model =& $this->getModel('DataBase');

      //get "matches"
      $matchList = $model->getMatchList();
      $this->matchList = $matchList; //assign variable to view

      //call parent function
      parent::display($template);
   }
}
