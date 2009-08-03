<?php
//
// Copyright (C) 2005-2006. designIT.  All rights reserved.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact license@designit.com.au if any conditions of this licencing isn't clear to
// you.
//

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( "extension/phplist/lib//phplist_listuser.php" );


class phplist_user_attribute extends eZPersistentObject
{

  function phplist_user_attribute(&$row)
  {
    $this->eZPersistentObject( $row );
  }

  function &definition()
  {
    return array( 'fields' => array(
                    'id' => array(
                      'name' => 'id',
                      'datatype' => 'integer',
                      'default' => 0,
                      'required' => true ),
                    'name' => array(
                      'name' => 'name',
                      'datatype' => 'string',
                      'default' => '',
                      'required' => true ),
                    'type' => array(
                      'name' => 'type',
                      'datatype' => 'string',
                      'default' => '',
                      'required' => false ),
                    'listorder' => array(
                      'name' => 'listorder',
                      'datatype' => 'integer',
                      'default' => 0,
                      'required' => true ),
                    'default_value' => array(
                      'name' => 'default_value',
                      'datatype' => 'string',
                      'default' => '',
                      'required' => true ),
                    'required' => array(
                      'name' => 'required',
                      'datatype' => 'boolean',
                      'default' => false,
                      'required' => true ),
                    'tablename' => array(
                      'name' => 'tablename',
                      'datatype' => 'string',
                      'default' => '',
                      'required' => true ),
                  ),
                  'keys' => array( 'id' ),
                  'function_attributes' => array(
                  ),
                  'increment_key' => 'id',
                  'class_name' => 'phplist_user_attribute',
                  'name' => 'phplist_user_attribute' );
  }
  
  function &create()
  {
    return new phplist_user_attribute( $row );
  }

  function &store()
  {
    eZPersistentObject::store();
    // add History entry
  }

  function getAttributeValueID($searchItem)
  {
    $output=array();
    if ($searchItem)
    {
      $db =& eZDB::instance();
      $query="SELECT id FROM phplist_listattr_".$this->attribute('tablename')." WHERE name = '".$searchItem."'";
      $result = $db->arrayQuery($query);
      foreach ($result as $row)
      {
        $output[]=$row['id'];
      }
    }
    return join(',',$output);
  }

}

?>
 
 
