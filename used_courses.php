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


require_once('../../config.php');

echo '<style>
    .text-center {
        text-align: center;
    }
    .bold-text {
        font-weight: bold;
    }
</style>';

// Obtém o ID da categoria a partir da URL
$categoryid = required_param('categoryid', PARAM_INT);

// Consulta o nome da categoria a partir do ID
$categoryname = $DB->get_field('report_coursestatsv2_cat', 'name', ['id' => $categoryid], MUST_EXIST);

$PAGE->set_url(new moodle_url('/report/coursestats_v2/details.php', ['categoryid' => $categoryid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('coursesused', 'report_coursestats_v2'));
$PAGE->set_heading(get_string('coursesused', 'report_coursestats_v2'));

echo $OUTPUT->header();

// Exibe o nome da categoria no topo
// Link para voltar à página anterior (index.php)
$back = html_writer::link(new moodle_url('/report/coursestats_v2/table_categories.php'), get_string('backtocategories', 'report_coursestats_v2'));

$sql = "SELECT * FROM {report_coursestatsv2_course} rcc 
        JOIN {report_coursestatsv2} rc ON rcc.courseid = rc.courseid
        WHERE rcc.coursestats_category_id = :categoryid";

$params = ['categoryid' => $categoryid];

$used_courses = $DB->get_records_sql($sql, $params);

echo $OUTPUT->heading($categoryname . ' (' . $back . ')', 4, 'text-center');

$usage_table = new html_table();
$usage_table->head = [
    get_string('courses', 'report_coursestats_v2'),
];


foreach ($used_courses as $course){
    $usage_table->data[] = [html_writer::link(
        new moodle_url('/course/view.php?id='.$course->courseid),
        format_string($course->name)
    )];
}

echo html_writer::table($usage_table);

echo $OUTPUT->footer();