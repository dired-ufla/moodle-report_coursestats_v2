<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
* Report settings
*
* @package    report
* @copyright  2024 CAPES/UFLA
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

defined('MOODLE_INTERNAL') || die;

define('REPORT_COURSESTATS_V2_NULL_USAGE_TYPE', 'null');
define('REPORT_COURSESTATS_V2_FORUM_USAGE_TYPE', 'forum');
define('REPORT_COURSESTATS_V2_REPOSITORY_USAGE_TYPE', 'repository');
define('REPORT_COURSESTATS_V2_ACTIVITY_USAGE_TYPE', 'activity');
define('REPORT_COURSESTATS_V2_PLUGIN_TABLE_NAME', 'report_coursestatsv2');
define('REPORT_COURSESTATS_V2_PLUGIN_MODULES_TABLE_NAME', 'report_coursestatsv2_mod');
define('REPORT_COURSESTATS_V2_NEWS_FORUM_NAME', 'news');
define('REPORT_COURSESTATS_V2_FORUM_TABLE_NAME', 'forum');
define('REPORT_COURSESTATS_V2_COURSE_TABLE_NAME', 'course');
define('REPORT_COURSESTATS_V2_MODULES_TABLE_NAME', 'modules');
define('REPORT_COURSESTATS_V2_REPOSITORY_MODULES', serialize(array('resource', 'url', 'folder')));
