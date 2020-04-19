<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Table class for data base tabel #__vwkmatch
 */
class VWKTableMatch extends JTable
{
   var $id = null;
   var $name = null;
   var $publish = null;
   var $ordering = null;

   /**
    * Constructor
    *
    * @param object Database connector object
    */
   function __construct(&$db)
   {
      parent::__construct('#__vwkmatch', 'id', $db);
   }
}
