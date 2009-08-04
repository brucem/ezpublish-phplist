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

class phplist_list extends eZPersistentObject
{

    function phplist_list( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
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
            'description' => array(
                'name' => 'description',
                'datatype' => 'string',
                'default' => '',
                'required' => false ),
            'entered' => array(
                'name' => 'entered',
                'datatype' => 'date',
                'default' => '',
                'required' => false ),
            'modified' => array(
                'name' => 'modified',
                'datatype' => 'date',
                'default' => '',
                'required' => false ),
            'listorder' => array(
                'name' => 'listorder',
                'datatype' => 'integer',
                'default' => 0,
                'required' => false ),
            'prefix' => array(
                'name' => 'prefix',
                'datatype' => 'string',
                'default' => '',
                'required' => false ),
            'rssfeed' => array(
                'name' => 'rssfeed',
                'datatype' => 'string',
                'default' => '',
                'required' => false ),
            'active' => array(
                'name' => 'active',
                'datatype' => 'boolean',
                'default' => false,
                'required' => false ),
            'owner' => array(
                'name' => 'owner',
                'datatype' => 'integer',
                'default' => 0,
                'required' => false ),
        ),
        'keys' => array( 'id' ),
        'function_attributes' => array(
            ),
            'increment_key' => 'id',
            'class_name' => 'phplist_list',
            'name' => 'phplist_list' );
    }

    static function create()
    {
        $row = array( 'entered' => date('Y-m-d H:i:s') );
        return new phplist_list( $row );
    }

    function store( $fieldFilters = null )
    {
        $this->setAttribute( 'modified', date( 'YmdHis' ) );
        eZPersistentObject::store();
        // add History entry
    }

}

?>
