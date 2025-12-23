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
require_login();

// Get the category ID from the URL
$categoryid = required_param('categoryid', PARAM_INT);

// Query the category name from the ID
$categoryname = $DB->get_field('report_coursestatsv2_cat', 'name', ['id' => $categoryid], MUST_EXIST);

$PAGE->set_url(new moodle_url('/report/coursestats_v2/details.php', ['categoryid' => $categoryid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('categorydetails', 'report_coursestats_v2'));

echo $OUTPUT->header();

// Display the category name at the top
// Link to return to the previous page (index.php)
$back = html_writer::link(new moodle_url('/report/coursestats_v2/table_categories.php'), get_string('backtocategories', 'report_coursestats_v2'));

$query1 = "SELECT count(*) AS amount FROM {report_coursestatsv2_course} 
          JOIN {report_coursestatsv2} 
          ON {report_coursestatsv2_course}.courseid = {report_coursestatsv2}.courseid
          WHERE {report_coursestatsv2_course}.coursestats_category_id = :category
          AND {report_coursestatsv2}.curr_usage_type = :type";
          
$params1 = ['category' => $categoryid, 'type' => 'forum'];
$params2 = ['category' => $categoryid,'type'=> 'repository'];
$params3 = ['category' => $categoryid,'type'=> 'activity'];

$forum = $DB->get_record_sql($query1, $params1);
$repository = $DB->get_record_sql($query1, $params2);
$activity = $DB->get_record_sql($query1, $params3);

$allCoursesUsage = $forum->amount + $repository->amount + $activity->amount;
$allRepositoryAndActivityCourses = $repository->amount + $activity->amount;

$percentageForum = ($forum->amount > 0 ? round(($forum->amount / $allCoursesUsage) * 100, 2) : 0); 

$percentageRepository = ($repository->amount > 0 ? round(($repository->amount / $allCoursesUsage) * 100, 2) : 0); 

$percentageActivity = ($activity->amount > 0 ? round(($activity->amount / $allCoursesUsage) * 100, 2) : 0); 

// First table: Types of Use
echo $OUTPUT->heading($categoryname . ' (' . $back . ')', 4, 'text-center');
echo $OUTPUT->heading(get_string('categorydetails', 'report_coursestats_v2'));

$usage_table = new html_table();
$usage_table->head = [
    get_string('usagetype', 'report_coursestats_v2'),
    get_string('roomcount', 'report_coursestats_v2'),
    get_string('usagerate', 'report_coursestats_v2')
];

$usage_table->data[] = [get_string('usageForum', 'report_coursestats_v2'), $forum->amount, $percentageForum."%"];
$usage_table->data[] = [get_string('usageRepository', 'report_coursestats_v2'), $repository->amount, $percentageRepository."%"];
$usage_table->data[] = [get_string('usageActivity', 'report_coursestats_v2'), $activity->amount, $percentageActivity."%"];
$usage_table->data[] = [html_writer::tag('strong', get_string('amount', 'report_coursestats_v2')),
                        html_writer::tag('strong', $allCoursesUsage),
                        html_writer::tag('strong', ($allCoursesUsage > 0 ? 100 : 0) . "%")];

echo html_writer::table($usage_table);

$query2 = "SELECT M.name, M.id, COUNT(CM.id) AS amount 
    FROM {modules} AS M 
    INNER JOIN {report_coursestatsv2_mod} AS CM ON M.id = CM.moduleid 
    INNER JOIN {report_coursestatsv2} AS C ON CM.courseid = C.courseid
    INNER JOIN {report_coursestatsv2_course} AS RCC ON C.courseid = RCC.courseid
    WHERE RCC.coursestats_category_id = :category GROUP BY M.name, M.id";


$param = ['category' => $categoryid];
$data = $DB->get_records_sql($query2, $param);

if (class_exists('core\chart_pie')) {
    $chart = new core\chart_pie();

    $serie = new core\chart_series(get_string('usagestats', 'report_coursestats_v2'), [$percentageForum, $percentageRepository, $percentageActivity]);
    $chart->add_series($serie);
    $chart->set_labels(
        [
            get_string('usageForum', 'report_coursestats_v2'),
            get_string('usageRepository', 'report_coursestats_v2'),
            get_string('usageActivity', 'report_coursestats_v2'),
        ]
    );

    echo $OUTPUT->render_chart($chart, false);
}

// Second table: Modules   
echo $OUTPUT->heading(get_string('modulesdetails', 'report_coursestats_v2'));

if (method_exists($OUTPUT,'paragraph')) {
    echo $OUTPUT->paragraph(get_string('modulesdetailsinfo', 'report_coursestats_v2'));
} else {
    echo $OUTPUT->container(get_string('modulesdetailsinfo', 'report_coursestats_v2'));
}

 $modules_table = new html_table();
 $modules_table->head = [
    get_string('modules', 'report_coursestats_v2'),
    get_string('roomcount', 'report_coursestats_v2'),
    get_string('usagerate', 'report_coursestats_v2')
 ];

 foreach ($data as $item) {
    $row = array();

    if($allRepositoryAndActivityCourses > 0){
    $percent = ($item->amount > 0 ? round(($item->amount / $allRepositoryAndActivityCourses) * 100, 2) : 0);
    }

    $row[] = $item->name;
    $row[] = $item->amount;
    $row[] = $percent."%";

    $modules_table->data[] = $row;
}
 

echo html_writer::table($modules_table);


if (class_exists('core\chart_bar')) {
    $chart = new core\chart_bar();
    
    $labels = [];
    $values = [];
    
    foreach ($data as $item) {
        $labels[] = $item->name;
        $values[] = round(($item->amount / $allRepositoryAndActivityCourses) * 100, 2); 
    }

    $series = new core\chart_series(get_string('modulesusagepercentage', 'report_coursestats_v2'), $values);

    $chart->set_labels($labels);
    $chart->add_series($series);
    $chart->get_xaxis(0, true)->set_label(get_string('modules', 'report_coursestats_v2'));
    $chart->get_yaxis(0, true)->set_label(get_string('percentageUse', 'report_coursestats_v2'));

    echo $OUTPUT->render_chart($chart, false);
}

echo $OUTPUT->footer();