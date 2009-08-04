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

class phplistFunctionCollection
{

    function phplistFunctionCollection()
    {
    }

    function fetchLists()
    {
        $conds = array( 'active' => 1);
        $sorts = array( 'listorder' => 'asc');
        $result = phplist_list::fetchObjectList( phplist_list::definition(), null, $conds, $sorts);
        return array( 'result' => $result );
    }

    function fetchList($listID)
    {
        $conds = array( 'id' => $listID);
        $result = phplist_list::fetchObject( phplist_list::definition(), null, $conds);
        return array( 'result' => $result );
    }

} 

?>
