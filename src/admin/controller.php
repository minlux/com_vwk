<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');
jimport( 'joomla.filesystem.path' );
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

//import "helper class" to do XSLT transformation
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'utilities'.DS.'xsltransform.php');

/**
 * Vereinswettkampf Component Controller (Admin)
 */
class VWKController extends JController
{
   public function __construct()
   {
      parent::__construct();

      //get model
      $model =& $this->getModel('DataBase');

      //set model for "ctrlpanel" view
      $view = $this->getView('ctrlpanel', 'html', 'VWKView');
      $view->setModel($model, true);

      //set model for "matchedit" view
      $view = $this->getView('matchedit', 'html', 'VWKView');
      $view->setModel($model, true);

      //set model for "resultedit" view
      $view = $this->getView('resultedit', 'html', 'VWKView');
      $view->setModel($model, true);
   }


   private function _getIntArray($varName)
   {
      $intArray = array();
      $varArray = JRequest::getVar($varName, array(), 'post', 'array');
      foreach ($varArray as $var)
      {
         $intArray[] = intval($var);
      }
      return $intArray;
   }


   private function _save()
   {
      //get objects
      $record = array();   //default record
      $model =& $this->getModel('DataBase');   //model
      $view = JRequest::getCmd('view', 'ctrlpanel');  //view

      //switch by the view
      switch ($view)
      {
      case 'matchedit':
         //assemble record
         $id = JRequest::getInt('mid', null);
         $record['id'] = $id;
         $name = JRequest::getVar('name');
         $record['name'] = $name;
         //save into database
         $id = $model->saveMatch($record);
         //set teamId and View
         JRequest::setVar('mid', $id);
         JRequest::setVar('view', 'matchedit');
         break;


      case 'resultedit':
         //assemble record
         $id = JRequest::getInt('rid', null);
         $record['id'] = $id;
         $match = JRequest::getInt('match', null);
         $record['matchid'] = $match;
         $name = JRequest::getVar('name');
         $record['name'] = $name;
         //get uploaded file
         $xmlFile = $_FILES['file']['tmp_name'];
         if ($xmlFile != null)
         {
            $xsltProc = new C_XslTransform();
            $xslFile = JPATH_COMPONENT_ADMINISTRATOR.DS.'wmshot2xml.xsl';

            //load xml file into DOM
            $xmlDOM = new DomDocument();
            $xmlDOM->formatOutput = true;
            $xmlDOM->load($xmlFile);

            //do transformation
            $xmlDOM = $xsltProc->transformToDOC($xmlDOM, $xslFile);
            $xmlString = $xmlDOM->saveXML();

            //add xml string and timestamp of update to record
            $record['xml'] = $xmlString;
            $record['updatetime'] = date('Y-m-d H:i:s');
         }
         //save into database
         $id = $model->saveResult($record);
         //set teamId and View
         JRequest::setVar('rid', $id);
         JRequest::setVar('view', 'resultedit');
         break;


      default:
         break;
      }
   }


   public function display()
   {
      $view = JRequest::getCmd('view', 'ctrlpanel');
      JRequest::setVar('view', $view);
      parent::display();
   }


   public function addMatch()
   {
      //display
      JRequest::setVar('mid', 0);
      JRequest::setVar('view', 'matchedit');
      parent::display();
   }


   public function addResult()
   {
      //display
      JRequest::setVar('rid', 0);
      JRequest::setVar('view', 'resultedit');
      parent::display();
   }


   /* switch between teamedit and matchedit by the value of cid[0] */
   public function edit()
   {
      //set default view
      JRequest::setVar('view', 'ctrlpanel');

      //get 1st match id
      $midArray = JRequest::getVar('mid', array(), 'default', 'array');
      $mid = (($midArray != null) ? intval($midArray[0]) : 0);
      if ($mid > 0)
      {
         //set matchId and View
         JRequest::setVar('mid', $mid);
         JRequest::setVar('view', 'matchedit');
      }
      else
      {
         //get 1st result id
         $ridArray = JRequest::getVar('rid', array(), 'default', 'array');
         $rid = (($ridArray != null) ? intval($ridArray[0]) : 0);
         if ($rid > 0)
         {
            //set resultId and View
            JRequest::setVar('rid', $rid);
            JRequest::setVar('view', 'resultedit');
         }
      }

      //display view
      parent::display();
   }


   /* save */
   public function apply()
   {
      //save data
      $this->_save();
      //display view
      parent::display();
   }


   /* save and close */
   public function save()
   {
      //save data
      $this->_save();
      //set redirection
      $this->setRedirect('index.php?option=com_vwk');
   }


   /* close */
   public function cancel()
   {
      //set redirection
      $this->setRedirect('index.php?option=com_vwk');
   }


   /* publish */
   public function publish($state = 1)
   {
      //get model
      $model =& $this->getModel('DataBase');
      $state = (($state != 0) ? 1 : 0); //do binarization

      //set matches 'published' in database
      $midArray = $this->_getIntArray('mid');
      $model->publishMatch($midArray, $state);

      //set results 'published' in database
      $ridArray = $this->_getIntArray('rid');
      $model->publishResult($ridArray, $state);

      //set redirection
      $this->setRedirect('index.php?option=com_vwk');
   }


   /* unpublish */
   public function unpublish()
   {
      $this->publish(0);
   }


   /* delete the selected table entries */
   public function remove()
   {
      //get model
      $model =& $this->getModel('DataBase');

      //delete selected results from database
      $ridArray = $this->_getIntArray('rid');
      $model->deleteResult($ridArray);

      //delete matches from database
      $midArray = $this->_getIntArray('mid');
      $model->deleteMatch($midArray);

      //set redirection
      $this->setRedirect('index.php?option=com_vwk');
   }


   private function _getPrevSiblingId(& $recordArray, $id)
   {
      //search for the id in the array
      $thisId = null;
      $prevId = null;
      foreach ($recordArray as $record)
      {
         //skip loop if the selected id was found in the array
         if ($record->id == $id)
         {
            $thisId = $record->id;
            break;
         }
         //otherwise, save id of the selected entry for the next loop
         $prevId = $record->id;
      }

      //evaluate if "search" was successfully
      if (($thisId == null) || ($prevId == null)) //on failure
      {
         $prevId = null;
      }
      return $prevId;
   }


   private function _getNextSiblingId(& $recordArray, $id)
   {
      //search for the id in the array
      $thisId = null;
      $nextId = null;
      foreach ($recordArray as $record)
      {
         $nextId = $record->id;
         //skip if id was alredy found
         if ($thisId != null)
         {
            break;
         }

         //otherwise, continue
         //set thisId if the selected id was found in the array
         if ($record->id == $id)
         {
            $thisId = $record->id;
         }
      }

      //evaluate if "search" was successfully
      if (($thisId == null) || ($nextId == null)) //on failure
      {
         $nextId = null;
      }
      return $nextId;
   }


   /* swap selected row with the previous / next row */
   private function _orderX($getSiblingId)
   {
      //get model
      $model =& $this->getModel('DataBase');

      //get 1st match id
      $midArray = JRequest::getVar('mid', array(), 'default', 'array');
      $mid = (($midArray != null) ? intval($midArray[0]) : 0);
      if ($mid > 0)
      {
         //get array of match records
         $matchOrdering = $model->getMatchOrdering();
         $siblingId = $this->$getSiblingId($matchOrdering, $mid); //call variable function - to get either previous or next sibling
         if ($siblingId != null) //if a sibling was found
         {
            $model->swapMatchOrdering($mid, $siblingId);
         }
      }
      else
      {
         //get 1st result id
         $ridArray = JRequest::getVar('rid', array(), 'default', 'array');
         $rid = (($ridArray != null) ? intval($ridArray[0]) : 0);
         if ($rid > 0)
         {
            //get result details as I need to know the parent match id
            $result = $model->getResult($rid);
            if ($result != null)
            {
               //get array of match records
               $matchResultOrdering = $model->getMatchResultOrdering($result->matchid);
               $siblingId = $this->$getSiblingId($matchResultOrdering, $rid); //call variable function - to get either previous or next sibling
               if ($siblingId != null) //if a sibling was found
               {
                  $model->swapResultOrdering($rid, $siblingId);
               }
            }
         }
      }

      //set redirection
      $this->setRedirect('index.php?option=com_vwk');
   }


   /* swap selected row with the row above */
   public function orderUp()
   {
      $this->_orderX('_getPrevSiblingId');
   }

   /* swap selected row with the row below */
   public function orderDown()
   {
      $this->_orderX('_getNextSiblingId');
   }
}

