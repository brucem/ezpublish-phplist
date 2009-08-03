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
// Contact license@designit.com.au if any conditions of this licencing isn't
// clear to you.
//

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( 'kernel/classes/ezpersistentobject.php' );


class phplist_listuser extends eZPersistentObject
{

  function phplist_listuser(&$row)
  {
    $this->eZPersistentObject( $row );
  }

  function &definition()
  {
    return array( 'fields' => array(
                    'userid' => array(
                      'name' => 'userid',
                      'datatype' => 'integer',
                      'default' => 0,
                      'required' => true ),
                    'listid' => array(
                      'name' => 'listid',
                      'datatype' => 'integer',
                      'default' => 0,
                      'required' => true ),
                    'entered' => array(
                      'name' => 'entered',
                      'datatype' => 'date',
                      'default' => '',
                      'required' => true ),
                    'modified' => array(
                      'name' => 'modified',
                      'datatype' => 'date',
                      'default' => '',
                      'required' => true ),
                  ),
                  'keys' => array( 'userid', 'listid' ),
                  'function_attributes' => array(
                  ),
                  'class_name' => 'phplist_listuser',
                  'name' => 'phplist_listuser' );
  }
  
  function &create()
  {
    $row=array('entered' => date('Y-m-d H:i:s'));
    return new phplist_listuser( $row );
  }

  function &store()
  {
    $this->setAttribute('modified', date('YmdHis'));
    eZPersistentObject::store();
    // add History entry
  }

  function &remove()
  {
    eZPersistentObject::remove();
    // add History entry
  }


  function &fetchByUserIDListID($userid=null, $listid=null)
  {
    if ($userid==null || $listid==null)
      return null;
    $conds = array( 'userid' => $userid,
                    'listid' => $listid);
    return phplist_listuser::fetchObject( phplist_listuser::definition(), null, $conds);
  }

}

?>

