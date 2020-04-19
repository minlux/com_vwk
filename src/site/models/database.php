<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');


class VWKModelDataBase extends JModel
{
   public function getMatchList()
   {
      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__vwkmatch WHERE publish = 1 ORDER BY ordering ASC';
      $db->setQuery($query);

      //get array of team records from database
      $matchList = $db->loadObjectList();
      return $matchList;
   }


   public function getMatch($id)
   {
      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__vwkmatch WHERE id = ' . $id . ' AND publish = 1 ORDER BY ordering ASC';
      $db->setQuery($query);

      //get array of team records from database
      $matchList = $db->loadObjectList();
      return $matchList[0];
   }


   public function getMatchResults($id)
   {
      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__vwkresult WHERE matchid = ' . $id . ' AND publish = 1 ORDER BY ordering ASC';
      $db->setQuery($query);

      //get array of team records from database
      $matchResults = $db->loadObjectList();
      return $matchResults;
   }
}
