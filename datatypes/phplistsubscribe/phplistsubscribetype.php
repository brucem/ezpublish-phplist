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
// Include the super class file
include_once( "kernel/classes/ezdatatype.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "extension/phplist/lib//phplist_user.php" );

// Define the name of datatype string
define( "EZ_DATATYPESTRING_PHPLISTSUBSCRIBE", "phplistsubscribe" );


class phplistsubscribeType extends eZDataType
{
  /*!
   Construction of the class, note that the second parameter in eZDataType 
   is the actual name showed in the datatype dropdown list.
  */
  function phplistsubscribeType()
  {
    $this->eZDataType( EZ_DATATYPESTRING_PHPLISTSUBSCRIBE, "PHPlist Subscribe" );
  }

   /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataInt = $originalContentObjectAttribute->attribute( "data_int" );
            $contentObjectAttribute->setAttribute( "data_int", $dataInt );
        }
        else
        {
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
            $default = $contentClassAttribute->attribute( "data_int3" );
            $contentObjectAttribute->setAttribute( "data_int", $default );
        }
    }



  /*!
    Validates the input and returns true if the input was
    valid for this datatype.
  */
  function validateObjectAttributeHTTPInput( &$http, $base, 
                                               &$contentObjectAttribute )
  {
    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
  }

  function deleteStoredObjectAttribute( &$contentObjectAttribute, $version = null )
  {
    $contentObjectID = $contentObjectAttribute->attribute('contentobject_id');
    $db =& eZDB::instance();
    $res = $db->arrayQuery( "SELECT COUNT(*) AS version_count FROM ezcontentobject_version WHERE contentobject_id = $contentObjectID" );
    $versionCount = $res[0]['version_count'];
    if ( $version == null || $versionCount <= 1 )
    {
      $phplistuser =& phplist_user::fetchByForeignkey($contentObjectID);
      if ($phplistuser != null)
        $phplistuser->remove();
    }
  }

 /*!
 */

   function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
   {
     if ( $http->hasPostVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) ))
     {
       $data = $http->postVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) );
       if ( isset( $data ) && $data !== '0' && $data !== 'false' )
         $data = 1;
       else
         $data = 0;
     }
     else
     {
       $data = 0;
     }
     $contentObjectAttribute->setAttribute( "data_int", $data );
     return true;
  }

  function onPublish( &$contentObjectAttribute, &$contentObject, &$publishedNodes )
  {
    $hasContent = $contentObjectAttribute->hasContent();
    if ( $hasContent )
    {
      $data = $contentObjectAttribute->content();
      // Fetch  phplist user
      $contentObjectID = $contentObjectAttribute->attribute('contentobject_id');
      $phplistuser =& phplist_user::fetchByForeignkey($contentObjectID);
      // If there isn't a user with this Foreignkey create one
      if ($phplistuser == null)
      {
        $phplistuser =& phplist_user::create();
        $phplistuser->setAttribute('foreignkey', $contentObjectID);
      }
      // Check that the email address is the same and update if required
      $userObject =& eZUser::fetch( $contentObjectID );
      $phplistuser->setAttribute('email', $userObject->attribute('email'));
      $phplistuser->store();
      // Depending on value of attribute modify subscription
      $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
      $listID    = $contentClassAttribute->attribute( 'data_int1' );
      if ($listID > 0 && $data)
        $phplistuser->subscribe($listID);
      else
        $phplistuser->unsubscribe($listID);
     }
     // Map attributes
     $phplistuser->mapAttributes($contentObject);
   }

  /*!
   Store the content. Since the content has been stored in function 
   fetchObjectAttributeHTTPInput(), this function is with empty code.
  */
  function storeObjectAttribute( &$contentObjectattribute )
  {
  }

  /*!
   Returns the meta data used for storing search indices.
  */
  function metaData( $contentObjectAttribute )
  {
    return $contentObjectAttribute->attribute( "data_int" );
  }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }


    /*!
     \reimp
    */
    function &sortKey( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    /*!
     \reimp
    */
    function &sortKeyType()
    {
        return 'int';
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }

    /*!
     Returns the integer value.
    */
    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return true;
    }


    /*!
     Sets the default value.
    */


/* Class Attribute functions */
  function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
  {
    if ( $http->hasPostVariable( $base . '_phplistsubscribe_list_default_value_' . $classAttribute->attribute( 'id' ) . '_exists' ) )
    {
      if ( $http->hasPostVariable( $base . "_phplistsubscribe_list_default_value_" . $classAttribute->attribute( "id" ) ))
      {
          $data = $http->postVariable( $base . "_phplistsubscribe_list_default_value_" . $classAttribute->attribute( "id" ) );
          if ( isset( $data ) )
              $data = 1;
          $classAttribute->setAttribute( "data_int3", $data );
      }
      else
      {
          $classAttribute->setAttribute( "data_int3", 0 );
      }
    }

    $defaultValueList = $base . '_phplistsubscribe_list_'  . $classAttribute->attribute( 'id' );
    $returnvalue = false;
    if ( $http->hasPostVariable( $defaultValueList ) )
    {
      $defaultValueListValue = $http->postVariable( $defaultValueList);

      if ($defaultValueListValue != -1)
      {
        $classAttribute->setAttribute( 'data_int1', $defaultValueListValue );
        $returnvalue=true;
      }
    }
    return $returnvalue;
  }

	/*!
	 \reimp
	*/
	function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
	{
		$defaultValue = $classAttribute->attribute( 'data_int3' );
		$attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-value',
																				 array( 'is-set' => $defaultValue ? 'true' : 'false' ) ) );
	}
	
	/*!
	 \reimp
	*/
	function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
	{
		$defaultValue = strtolower( $attributeParametersNode->elementTextContentByName( 'default-value' ) ) == 'true';
		$classAttribute->setAttribute( 'data_int3', $defaultValue );
	}
	

}
eZDataType::register( EZ_DATATYPESTRING_PHPLISTSUBSCRIBE, "phplistsubscribetype" );
