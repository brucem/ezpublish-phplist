#!/usr/bin/env php
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

set_time_limit( 0 );

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli = eZCLI::instance();
$endl = $cli->endlineString();

$script = eZScript::instance( array( 'description' => ( "eZ publish to phpList user Import.\n\n".
    "Goes trough all objects with phplistsubscribe attributes and imports and or refreshes user details into phpList." .
    "\n" .
    "eztophplist.php" ),
'use-session' => true,
'use-modules' => true,
'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( '[sql]', '', array( 'sql' => 'Display sql queries' ) );
$script->initialize();
$showDebug = false;
$showSQL = $options['sql'] ? true : false;
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;

if ( $siteAccess )
{
    changeSiteAccessSetting( $siteaccess, $siteAccess );
}

function changeSiteAccessSetting( $siteaccess, $optionData )
{
    global $isQuiet;
    $cli = eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for ez to phplist import" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

include_once( 'kernel/classes/ezcontentclassattribute.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( "extension/phplist/lib/phplist_user.php" );

$db = eZDB::instance();
$db->setIsSQLOutputEnabled( $showSQL );

$phplistSubscribeTypeAttributeList = eZContentClassAttribute::fetchList( true, array( 'data_type' => 'phplistsubscribe',
    'version' => 0 ) );
$classAttributeIDList = array();
for ( $i = 0; $i < count( $phplistSubscribeTypeAttributeList ); ++$i )
{
    $phplistSubscribeTypeAttribute = $phplistSubscribeTypeAttributeList[$i];
    $classAttributeIDList[] = $phplistSubscribeTypeAttribute->attribute( 'id' );
}
unset( $phplistSubscribeTypeAttributeList );

$attributeCount = eZContentObjectAttribute::fetchListByClassID( $classAttributeIDList, false, 
    array( 'offset' => 0, 'length' => 3 ), false, true );
if ( $showDebug )
    print( "Attribute count = '$attributeCount'\n" );

$attributeOffset = 0;
$attributeLimit = 140;

$dotCount = 0;
$dotTotalCount = 0;
$dotMax = 70;

while ( $attributeOffset < $attributeCount )
{
    $percent = ( $dotTotalCount * 100.0 ) / ( $attributeCount - 1 );
    unset( $objectAttributeList );
    $objectAttributeList = eZContentObjectAttribute::fetchListByClassID( $classAttributeIDList, false, array( 'offset' => $attributeOffset,
        'length' => $attributeLimit ),
    true, false );
    $lastID = false;
    for ( $i = 0; $i < count( $objectAttributeList ); ++$i )
    {
        $percent = ( $dotTotalCount * 100.0 ) / ( $attributeCount - 1 );
        $objectAttribute = $objectAttributeList[$i];
        $lastID = $objectAttribute->attribute( 'id' );
        $dataType = $objectAttribute->dataType();
        $handleAttribute = true;
        $badDataType = false;
        if ( !$dataType or get_class( $dataType ) != 'phplistsubscribetype' )
        {
            $handleAttribute = false;
            $badDataType = true;
        }
        if ( $handleAttribute )
        {
            $hasContent = $objectAttribute->hasContent();
            if ( $hasContent )
            {
                $data = $objectAttribute->content();
                // Fetch  phplist user
                $contentObjectID = $objectAttribute->attribute('contentobject_id');
                $phplistuser = phplist_user::fetchByForeignkey($contentObjectID);
                // If there isn't a user with this Foreignkey create one
                if ($phplistuser == null)
                {
                    $phplistuser = phplist_user::create();
                    $phplistuser->setAttribute('foreignkey', $contentObjectID);
                }
                // Check that the email address is the same and update if required
                $userObject = eZUser::fetch( $contentObjectID );
                $phplistuser->setAttribute('email', $userObject->attribute('email'));
                $phplistuser->store();
                // Depending on value of attribute modify subscription
                $contentClassAttribute = $objectAttribute->contentClassAttribute();
                $listID    = $contentClassAttribute->attribute( 'data_int1' );
                if ($listID > 0 && $data)
                    $phplistuser->subscribe($listID);
                else
                    $phplistuser->unsubscribe($listID);
            }
            // Map attributes
            $contentObject = eZContentObject::fetch( $contentObjectID );
            $phplistuser->mapAttributes($contentObject);
            print( '.' );
        }
        else
        {
            print( 'x' );
        }
        ++$dotCount;
        ++$dotTotalCount;
        if ( $dotCount >= $dotMax or $dotTotalCount >= $attributeCount )
        {
            $percent = number_format( ( $dotTotalCount * 100.0 ) / ( $attributeCount ), 2 );
            $dotSpace = '';
            if ( $dotTotalCount > $dotMax )
                $dotSpace = str_repeat( ' ', $dotMax - $dotCount );
            print( $dotSpace . " " . $percent . "% ( $dotTotalCount )\n" );
            $dotCount = 0;
        }
    }
    $attributeOffset += $attributeLimit;
}
print( "\n" );


print( "Number of attributes      : $attributeCount\n" );
$script->shutdown();

?>
