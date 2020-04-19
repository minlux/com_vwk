<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');


class VWKViewCtrlPanel extends JView
{
   function display($tpl = null)
   {
      //get model
      $model =& $this->getModel('DataBase');

      //get Match-Result-Tree
      $matchResultTree = $model->getMatchResultTree();
      $this->matchResultTree = $matchResultTree; //assign to view

      //add title to content
      JToolBarHelper::title('Vereinswettkampf Administration', 'generic.png');
      JToolBarHelper::addNew('addMatch', 'New Match');
      JToolBarHelper::addNew('addResult', 'Add Result');
      JToolBarHelper::editList();
      JToolBarHelper::deleteList();
      JToolBarHelper::divider();
      JToolBarHelper::publish();
      JToolBarHelper::unpublish();
      // Display the template
      parent::display($tpl);
   }
}
