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

class phplist_user_user_attribute extends eZPersistentObject
{

    function phplist_user_user_attribute( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array(
            'attributeid' => array(
                'name' => 'attributeid',
                'datatype' => 'integer',
                'default' => 0,
                'required' => true ),
            'userid' => array(
                'name' => 'userid',
                'datatype' => 'integer',
                'default' => 0,
                'required' => true ),
            'value' => array(
                'name' => 'value',
                'datatype' => 'string',
                'default' => '',
                'required' => false ),
        ),
        'keys' => array( 'attributeid', 'userid' ),
        'function_attributes' => array(
            'attribute' => 'getAttribute'
        ),
        'class_name' => 'phplist_user_user_attribute',
        'name' => 'phplist_user_user_attribute' );
    }

    static function create()
    {
        return new phplist_user_user_attribute( $row );
    }

    function store( $fieldFilters = null )
    {
        eZPersistentObject::store();
        // add History entry
    }

    function getAttribute()
    {
        $conds = array( 'id' => $this->attribute('attributeid'));
        return phplist_user_attribute::fetchObject( phplist_user_attribute::definition(), null, $conds);
    }

}

?>
