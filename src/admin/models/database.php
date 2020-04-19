<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
//set include path to "tables"
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vwk'.DS.'tables');

/**
 * Backend Model
 */
class VWKModelDataBase extends JModel
{
   public function getMatchResultTree()
   {
      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__vwkmatch ORDER BY ordering ASC';
      $db->setQuery($query);

      //get array of records from database
      $matchResultTree = $db->loadObjectList();

      //for each match: add results
      foreach ($matchResultTree as &$matchResult)
      {
         //query database for all "results" of the "match"
         $query = 'SELECT * FROM #__vwkresult WHERE matchid = ' . $matchResult->id . ' ORDER BY ordering ASC';
         $db->setQuery($query);

         //get array of records from database
         $resultList = $db->loadObjectList();
         $matchResult->result = $resultList;
      }

      //return hierarchical tree of matches and their results
      return $matchResultTree;
   }


   public function getMatchList()
   {
      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__vwkmatch ORDER BY ordering ASC';
      $db->setQuery($query);

      //get array of records from database
      $matchList = $db->loadObjectList();
      return $matchList;
   }


   public function getMatchOrdering()
   {
      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT id, ordering FROM #__vwkmatch ORDER BY ordering ASC';
      $db->setQuery($query);

      //get array of records from database
      $matchOrdering = $db->loadObjectList();
      return $matchOrdering;
   }


   public function getMatch($id)
   {
      //preconditional check
      if ($id == null)
      {
         return null;
      }

      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__vwkmatch WHERE id = ' . $id;
      $db->setQuery($query);

      //get array of database entries (rows)
      $matchList = $db->loadObjectList();
      return $matchList[0];
   }


   public function getMatchResultOrdering($id)
   {
      //preconditional check
      if ($id == null)
      {
         return null;
      }

      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT id, ordering FROM #__vwkresult WHERE matchid = ' . $id . ' ORDER BY ordering ASC';
      $db->setQuery($query);

      //get array of database entries (rows)
      $matchResultOrdering = $db->loadObjectList();
      return $matchResultOrdering;
   }


   public function getResult($id)
   {
      //preconditional check
      if ($id == null)
      {
         return null;
      }

      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__vwkresult WHERE id = ' . $id;
      $db->setQuery($query);

      //get array of database entries (rows)
      $resultList = $db->loadObjectList();
      return $resultList[0];
   }


   public function saveMatch(&$record)
   {
      //get table
      $table = &$this->getTable('Match', 'VWKTable');
      //bind record to table
      $retVal = $table->bind($record);
      if ($retVal == false)
      {
         $errMsg = $table->getError();
         JError::raiseWarning(500, $errMsg);
         return null;
      }
      $id = $table->id; //information needed later
      //store data
      $table->store();
      if ($retVal == false)
      {
         $errMsg = $table->getError();
         JError::raiseWarning(500, $errMsg);
         return null;
      }

      //finalize an insert query -> set ordering to id
      if (($id == null) && ($table->id != null))
      {
         $table->ordering = $table->id;
         $table->store();
      }
      //return the id
      return $table->id;
   }


   public function saveResult(&$record)
   {
      //get table
      $table = &$this->getTable('Result', 'VWKTable');
      //bind record to table
      $retVal = $table->bind($record);
      if ($retVal == false)
      {
         $errMsg = $table->getError();
         JError::raiseWarning(500, $errMsg);
         return null;
      }
      $id = $table->id; //information needed later
      //store data
      $table->store();
      if ($retVal == false)
      {
         $errMsg = $table->getError();
         JError::raiseWarning(500, $errMsg);
         return null;
      }

      //finalize an insert query -> set ordering to id
      if (($id == null) && ($table->id != null))
      {
         $table->ordering = $table->id;
         $table->store();
      }
      //return the id
      return $table->id;
   }


   public function publishMatch(&$idArray, $state)
   {
      //get database
      $db = &JFactory::getDBO();

      //set publish state for all "addressed" matches
      foreach ($idArray as $id)
      {
         $query = 'UPDATE #__vwkmatch SET publish = ' . $state . ' WHERE id = ' . $id;
         $db->setQuery($query);
         $db->query();
      }
   }


   public function publishResult(&$idArray, $state)
   {
      //get database
      $db = &JFactory::getDBO();

      //set publish state for all "addressed" results
      foreach ($idArray as $id)
      {
         $query = 'UPDATE #__vwkresult SET publish = ' . $state . ' WHERE id = ' . $id;
         $db->setQuery($query);
         $db->query();
      }
   }


   public function deleteMatch(&$idArray)
   {
      //get database
      $db = &JFactory::getDBO();

      //for each match
      foreach ($idArray as $id)
      {
         if ($id > 0)
         {
            //delete all matches related to the team id
            $query = 'DELETE FROM #__vwkresult WHERE matchid = ' . $id;
            $db->setQuery($query);
            $db->query();

            //delete "addressed" match
            $query = 'DELETE FROM #__vwkmatch WHERE id = ' . $id;
            $db->setQuery($query);
            $db->query();
         }
      }
   }


   public function deleteResult(&$idArray)
   {
      //get database
      $db = &JFactory::getDBO();

      //for each team
      foreach ($idArray as $id)
      {
         if ($id > 0)
         {
            //delete "addressed" team
            $query = 'DELETE FROM #__vwkresult WHERE id = ' . $id;
            $db->setQuery($query);
            $db->query();
         }
      }
   }


   /* swap value of column ordering for the "addressed" records */
   private function _swapOrdering($tableName, $idA, $idB)
   {
      //get table
      $table = &$this->getTable($tableName, 'VWKTable');
      if ($table != null)
      {
         //load record B
         $table->load($idB);
         $orderingB = $table->ordering;
         //load record A
         $table->load($idA);
         $orderingA = $table->ordering;

         //check both "ordering values" for being not null
         if (($orderingA != null) && ($orderingB != null))
         {
            //set ordering of record A to value of record B
            $table->ordering = $orderingB;
            //store data
            $table->store();

            //load record B (once again)
            $table->load($idB);
            //set ordering of record B to value of record A
            $table->ordering = $orderingA;
            //store data
            $table->store();
         }
      }
   }


   /* swap value of column ordering for the "addressed" match records */
   public function swapMatchOrdering($idA, $idB)
   {
      $this->_swapOrdering('Match', $idA, $idB);
   }

   /* swap value of column ordering for the "addressed" result records */
   public function swapResultOrdering($idA, $idB)
   {
      $this->_swapOrdering('Result', $idA, $idB);
   }

}
