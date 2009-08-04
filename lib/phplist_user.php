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

/**
 * phplist_user 
 * 
 * @package PHPList
 * @version //autogen//
 * @copyright Copyright (C) 2009 Bruce Morrison. All rights reserved.
 * @author Bruce Morrison <bruce.morrison@stuffandcontent.com> 
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class phplist_user extends eZPersistentObject
{

    function phplist_user( $row )
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
            'email' => array(
                'name' => 'email',
                'datatype' => 'string',
                'default' => '',
                'required' => true ),
            'confirmed' => array(
                'name' => 'confirmed',
                'datatype' => 'boolean',
                'default' => true,
                'required' => true ),
            'blacklisted' => array(
                'name' => 'blacklisted',
                'datatype' => 'boolean',
                'default' => false,
                'required' => true ),
            'bouncecount' => array(
                'name' => 'bouncecount',
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
            'uniqid' => array(
                'name' => 'uniqid',
                'datatype' => 'string',
                'default' => '',
                'required' => true ),
            'htmlemail' => array(
                'name' => 'htmlemail',
                'datatype' => 'boolean',
                'default' => true,
                'required' => true ),
            'subscribepage' => array(
                'name' => 'subscribepage',
                'datatype' => 'integer',
                'default' => 0,
                'required' => true ),
            'rssfrequency' => array(
                'name' => 'rssfrequency',
                'datatype' => 'string',
                'default' => '',
                'required' => true ),
            'password' => array(
                'name' => 'password',
                'datatype' => 'string',
                'default' => '',
                'required' => true ),
            'passwordchanged' => array(
                'name' => 'passwordchanged',
                'datatype' => 'date',
                'default' => '',
                'required' => true ),
            'disabled' => array(
                'name' => 'disabled',
                'datatype' => 'boolean',
                'default' => false,
                'required' => true ),
            'extradata' => array(
                'name' => 'extradata',
                'datatype' => 'string',
                'default' => '',
                'required' => true ),
            'foreignkey' => array(
                'name' => 'foreignkey',
                'datatype' => 'string',
                'default' => '',
                'required' => true ),
        ),
        'keys' => array( 'id' ),
        'function_attributes' => array(
            'subscriptions' => 'getSubscriptions',
            'attributes' => 'getAttributes',
            'userAttributes' => 'getUserAttributes',
        ),
        'increment_key' => 'id',
        'class_name' => 'phplist_user',
        'name' => 'phplist_user_user' );
    }

    static function create()
    {
        $row=array(
            'entered'   => date('Y-m-d H:i:s'),
            'extradata' => 'eZ publish user',
            'uniqid'    => md5(uniqid(mt_rand()))
        );
        return new phplist_user( $row );
    }

    function store( $fieldFilters = null )
    {
        $this->setAttribute('modified', date('YmdHis'));
        eZPersistentObject::store();
        // add History entry
    }

    function remove( $conditions = null, $extraConditions = null )
    {
        # Remove subscriptions
        $subscriptions = $this->attribute('subscriptions');
        foreach(array_keys($subscriptions) as $index)
        {
            $subscriptions[$index]->remove();
        }
        # Remove attributes
        $userAttributes = $this->attribute('userAttributes');
        foreach(array_keys($userAttributes) as $index)
        {
            $userAttributes[$index]->remove();
        }
        $userid = $this->attribute('id');
        $db = eZDB::instance();
        # Remove entries from phplist_usermessage
        $query = "DELETE FROM phplist_usermessage WHERE userid = $userid";
        $result = $db->query($query);

        # Remove entries from phplist_user_message_bounce
        $query = "DELETE FROM phplist_user_message_bounce WHERE user = $userid";
        $result = $db->query($query);

        # Remove entries from phplist_user_history
        $query = "DELETE FROM phplist_user_user_history WHERE userid = $userid";
        $result = $db->query($query);

        # Remove entries from phplist_user_rss
        $query = "DELETE FROM phplist_user_rss WHERE userid = $userid";
        $result = $db->query($query);

        # Remove user
        eZPersistentObject::remove();
    }


    static function fetch( $id=null )
    {
        if ($id==null)
            return null;
        $conds = array( 'id' => $id );
        return phplist_user::fetchObject( phplist_user::definition(), null, $conds);
    }


    static function fetchByForeignkey( $foreignkey=null )
    {
        if ($foreignkey==null)
            return null;
        $conds = array( 'foreignkey' => $foreignkey );
        return phplist_user::fetchObject( phplist_user::definition(), null, $conds );
    }

    function getSubscriptions()
    {
        $conds = array( 'userid' => $this->attribute( 'id' ) );
        return phplist_listuser::fetchObjectList( phplist_listuser::definition(), null, $conds);
    }

    static function getAttributes()
    {
        $conds = array();
        $sorts = array('listorder' => 'asc' );
        return phplist_user_attribute::fetchObjectList( phplist_user_attribute::definition(), null, $conds, $sorts);
    }

    function getUserAttributes()
    {
        $conds = array( 'userid' => $this->attribute('id'));
        return phplist_user_user_attribute::fetchObjectList( phplist_user_user_attribute::definition(), null, $conds);
    }

    function isSubscribed($listID)
    {
        $conds = array( 'userid' => $this->attribute('id'),
            'listid' => $listID);
        $sub = phplist_listuser::fetchObject( phplist_listuser::definition(), null, $conds);
        return ($sub != null);
    }

    function subscribe($listID)
    {
        //check if user is already subscribed
        if (! $this->isSubscribed($listID))
        {
            $sub = phplist_listuser::create();
            $sub->setAttribute('listid', $listID);
            $sub->setAttribute('userid', $this->attribute('id'));
            $sub->store();
        }
    }

    function unsubscribe($listID)
    {
        $sub = phplist_listuser::fetchByUserIDListID($this->attribute('id'), $listID);
        if ($sub != null)
            $sub->remove();
    }

    function mapAttributes($userObject=null)
    {
        if ($userObject != null)
        {
            // read inifile to get mappings
            $ini = eZINI::instance('phplist.ini');
            $attributeMap = $ini->hasVariable("AttributeMap", "MapEzPhplist" ) ? $ini->variable( "AttributeMap", "MapEzPhplist" ) : false;
            if ( is_array( $attributeMap ) && count( $attributeMap ) > 0)
            {
                $contentobjectAttributes = $userObject->attribute('contentobject_attributes');
                foreach ($contentobjectAttributes as $attribute)
                {
                    if (isset($attributeMap[$attribute->attribute('contentclassattribute_id')]))
                    {
                        // try and fecth the corresponding phplist attribute
                        $conds = array( 'userid' => $this->attribute('id'),
                            'attributeid' => $attributeMap[$attribute->attribute('contentclassattribute_id')]);
                        $phplistUserAttribute = phplist_user_user_attribute::fetchObject( phplist_user_user_attribute::definition(), null, $conds);
                        if ($phplistUserAttribute == null)
                        {
                            $phplistUserAttribute = phplist_user_user_attribute::create();
                            $phplistUserAttribute->setAttribute('userid', $this->attribute('id'));
                            $phplistUserAttribute->setAttribute('attributeid', $attributeMap[$attribute->attribute('contentclassattribute_id')]);
                        }
                        $content = $attribute->content();
                        if (is_array($content) || is_object ($content))
                            $content = $attribute->title();

                        $phplistAttribute = $phplistUserAttribute->attribute('attribute');
                        switch ($phplistAttribute->attribute('type'))
                        {
                            case 'select':
                            case 'radio':
                            {
                                $content=$phplistAttribute->getAttributeValueID($content);
                            } break;
                        }
                        $phplistUserAttribute->setAttribute('value', $content);
                        $phplistUserAttribute->store();
                    }
                }
            }
        }
    }

}

?>
