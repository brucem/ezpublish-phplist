<?php
/**
 * PHPList subscription synchronise extension for eZ Publish
 * Written by Bruce Morrison <bruce@stuffandcontent.com>
 * Copyright (C) 2005-2006 designIT. All rights reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

class PHPListInfo
{

    static function info( )
    {
        return array(
            'Name'      => 'PHPList subscription synchronise',
            'Version'   => '2.0',
            'Author'    => '<a href="http://www.stuffandcontent.com/">Bruce Morrison</a>',
            'Copyright' => 'Copyright (C) 2005-' . date( 'Y' ) . ' designIT',
            'License'   => 'GNU General Public License v2.0',
        );
    }
}
?>
