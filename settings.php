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

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
   // Add the link to the report in the Reports section.
    $ADMIN->add('reports', new admin_externalpage(
        'reportcoursestatsv2',
        get_string('pluginname', 'report_coursestats_v2'),
        "$CFG->wwwroot/report/coursestats_v2/index.php",
        'moodle/site:config'
    ));

// Add plugin configuration.
    $settings = new admin_settingpage('report_coursestats_v2', get_string('coursestatsv2_settings', 'report_coursestats_v2'));

    $settings->add(new admin_setting_configtextarea(
        'report_coursestats_v2/customcatnames',
        get_string('catnamechanger_text', 'report_coursestats_v2'),
        get_string('catnamechanger_desc', 'report_coursestats_v2'),
        '',
        PARAM_RAW,
        60,
        10
    ));

   // Add checkbox setting for sorting categories by name.
   $settings->add(new admin_setting_configcheckbox(
    'report_coursestats_v2/sortcategoriesbyname',
    get_string('sortcategoriesbyname', 'report_coursestats_v2'),
    get_string('sortcategoriesbyname_desc', 'report_coursestats_v2'),
    0 // Default value (false)
    ));

    $ADMIN->add('reports', $settings);
}
