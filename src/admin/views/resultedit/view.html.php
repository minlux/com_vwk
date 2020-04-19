<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');


class VWKViewResultEdit extends JView
{
   function display($tpl = null)
   {
      //get result id
      $id = JRequest::getInt('rid');
      $model =& $this->getModel('DataBase');

      //prepare default data
      $result = new stdClass();
      if ($id > 0)
      {
         //get model
         $result = $model->getResult($id);
      }
      $this->result = $result; //assign to view

      //get match list
      $matchList = $model->getMatchList();
      $this->matchList = $matchList; //assign to view

      //add title to content
      if ($id == 0)
      {
         JToolBarHelper::title('Ergebnis Administration [New]', 'generic.png');
      }
      else
      {
         JToolBarHelper::title('Ergebnis Administration [Edit]', 'generic.png');
      }
      JToolBarHelper::apply();
      JToolBarHelper::save();
      JToolBarHelper::cancel();

      // Display the template
      parent::display($tpl);
   }
}
