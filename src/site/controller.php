<?php
/* This file holds the default frontend controller, which is a class called {ComponentName}Controller.
   This class must extend the base class JController. */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * Vereinswettkampf Component Controller
 */
class VWKController extends JController
{
   public function __construct()
   {
      parent::__construct();

      //get model
      $model =& $this->getModel('DataBase');

      //set model for "matchlist" view
      $view = $this->getView('matchlist', 'html', 'VWKView');
      $view->setModel($model, true);

      //set model for "resultlists" view
      $view = $this->getView('resultlists', 'html', 'VWKView');
      $view->setModel($model, true);
   }


   function display()
   {
      $view = JRequest::getCmd('view', 'matchlist'); //use matchlist as fallback
      JRequest::setVar('view', $view);
      parent::display();
   }
}

