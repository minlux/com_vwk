<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Table class for data base tabel #__vwkresult
 */
class VWKTableResult extends JTable
{
   var $id = null;
   var $matchid = null;
   var $name = null;
   var $xml = null;
   var $updatetime = null;
   var $publish = null;
   var $ordering = null;

   /**
    * Constructor
    *
    * @param object Database connector object
    */
   function __construct(&$db)
   {
      parent::__construct('#__vwkresult', 'id', $db);
   }
}
