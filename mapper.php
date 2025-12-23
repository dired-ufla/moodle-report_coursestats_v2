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

function report_coursestats_v2_process_custom_config($configuration)
{
    // Make the database instance global to use it anywhere in the function
    global $DB;
    global $id;

    //Checking which database is being used to change the LIKE operator
    $dbtype = $DB->get_dbfamily();
    $like_operator = ($dbtype === 'postgres') ? 'ILIKE' : 'LIKE';

    // Split the new lines into an array, the explode function is used for this
    $lines = explode("\n", trim($configuration));

    // Variable to store the current category from which courses will be fetched from the database
    $currentCategory = '';

    // foreach to process each of the lines
    foreach ($lines as $line) {
        // The trim function removes spaces from the beginning and end of the line
        $line = trim($line);
        $filhas = false;

        // Check to determine if it is a category or a filter for the courses to be fetched
        if (strpos($line, ':') !== false && trim(substr($line, strpos($line, ':') + 1)) === '') {
            $currentCategory = rtrim($line, ':');
            $category = new stdClass();
            $category->name = $currentCategory;
            $id = $DB->insert_record('report_coursestatsv2_cat', $category);
        } elseif (strpos($line, ':') !== false) {
            if(strpos($line, '+') !== false){
                $filhas  = true;
            }
            // The list function assigns each variable a value from the passed array
            list($code, $filters) = explode(':', $line);
            $code = trim($code);
            $code = str_replace("+", "", $code);
            $filters = trim($filters);
            

            // Check if all courses are requested
            if ($filters == '*') {
                // Fetch all courses from the database
                $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code";
                $params = ['code' => $code];
                $courses = $DB->get_records_sql($query, $params);
                foreach ($courses as $course) {
                    $course_add = new stdClass();
                    $course_add->name = $course->shortname;
                    $course_add->courseid = $course->id;
                    $course_add->coursestats_category_id = $id;
                    $DB->insert_record('report_coursestatsv2_course', $course_add);
                }
                
                if($filhas){
                    $query = "SELECT id FROM {course_categories} WHERE visible = 1 and parent = :code";
                    $params = ['code' => $code];
                    $cat_ids = $DB->get_records_sql($query, $params);
                    foreach($cat_ids as $cat_id){
                        $query = "SELECT * FROM {course} WHERE visible = 1 and category = :cat";
                        $params = ['cat' => $cat_id->id];
                        $courses = $DB->get_records_sql($query, $params);
                        foreach ($courses as $course) {
                            $course_add = new stdClass();
                            $course_add->name = $course->shortname;
                            $course_add->courseid = $course->id;
                            $course_add->coursestats_category_id = $id;
                            $DB->insert_record('report_coursestatsv2_course', $course_add);
                        }
                    }
                }
            }
            // Check if there are multiple filter configurations to search in the database
            elseif (strpos($filters, ',') !== false) {
                $courses = explode(',', $filters);
                foreach ($courses as $course) {
                    $course = trim($course);

                    // Check if the filter wants to get courses that have a specific name
                    if (strpos($course, '%') !== false) {
                        // Fetch courses containing a specific name
                        if (strpos($course, '%') === 0 && strrpos($course, '%') === strlen($course) - 1) {
                            $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname $like_operator :course";
                            $params = ['code' => $code, 'course' => $course];
                            $results = $DB->get_records_sql($query, $params);
                            foreach ($results as $result) {
                                $course_add = new stdClass();
                                $course_add->name = $result->shortname;
                                $course_add->courseid = $result->id;
                                $course_add->coursestats_category_id = $id;
                                $DB->insert_record('report_coursestatsv2_course', $course_add);
                            }
                            if($filhas){
                                $query = "SELECT id FROM {course_categories} WHERE visible = 1 and parent = :code";
                                $params = ['code' => $code];
                                $cat_ids = $DB->get_records_sql($query, $params);
                                foreach($cat_ids as $cat_id){
                                    $query = "SELECT * FROM {course} WHERE visible = 1 and category = :cat and shortname $like_operator :course";
                                    $params = ['cat' => $cat_id->id, 'course' => $course];
                                    $results = $DB->get_records_sql($query, $params);
                                    foreach ($results as $result) {
                                        $course_add = new stdClass();
                                        $course_add->name = $result->shortname;
                                        $course_add->courseid = $result->id;
                                        $course_add->coursestats_category_id = $id;
                                        $DB->insert_record('report_coursestatsv2_course', $course_add);
                                    }
                                }
                            }
                            // Fetch courses that end with a specific name
                        } elseif (strpos($course, '%') === 0) {
                            $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname $like_operator :course";
                            $params = ['code' => $code, 'course' => $course];
                            $results = $DB->get_records_sql($query, $params);
                            foreach ($results as $result) {
                                $course_add = new stdClass();
                                $course_add->name = $result->shortname;
                                $course_add->courseid = $result->id;
                                $course_add->coursestats_category_id = $id;
                                $DB->insert_record('report_coursestatsv2_course', $course_add);
                            }
                            if($filhas){
                                $query = "SELECT id FROM {course_categories} WHERE visible = 1 and parent = :code";
                                $params = ['code' => $code];
                                $cat_ids = $DB->get_records_sql($query, $params);
                                foreach($cat_ids as $cat_id){
                                    $query = "SELECT * FROM {course} WHERE visible = 1 and category = :cat and shortname $like_operator :course";
                                    $params = ['cat' => $cat_id->id, 'course' => $course];
                                    $results = $DB->get_records_sql($query, $params);
                                    foreach ($results as $result) {
                                        $course_add = new stdClass();
                                        $course_add->name = $result->shortname;
                                        $course_add->courseid = $result->id;
                                        $course_add->coursestats_category_id = $id;
                                        $DB->insert_record('report_coursestatsv2_course', $course_add);
                                    }
                                }
                            }
                            // Fetch courses that start with a specific name
                        } elseif (strrpos($course, '%') === strlen($course) - 1) {
                            $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname $like_operator :course";
                            $params = ['code' => $code, 'course' => $course];
                            $results = $DB->get_records_sql($query, $params);
                            foreach ($results as $result) {
                                $course_add = new stdClass();
                                $course_add->name = $result->shortname;
                                $course_add->courseid = $result->id;
                                $course_add->coursestats_category_id = $id;
                                $DB->insert_record('report_coursestatsv2_course', $course_add);
                            }
                            if($filhas){
                                $query = "SELECT id FROM {course_categories} WHERE visible = 1 and parent = :code";
                                $params = ['code' => $code];
                                $cat_ids = $DB->get_records_sql($query, $params);
                                foreach($cat_ids as $cat_id){
                                    $query = "SELECT * FROM {course} WHERE visible = 1 and category = :cat and shortname $like_operator :course";
                                    $params = ['cat' => $cat_id->id, 'course' => $course];
                                    $results = $DB->get_records_sql($query, $params);
                                    foreach ($results as $result) {
                                        $course_add = new stdClass();
                                        $course_add->name = $result->shortname;
                                        $course_add->courseid = $result->id;
                                        $course_add->coursestats_category_id = $id;
                                        $DB->insert_record('report_coursestatsv2_course', $course_add);
                                    }
                                }
                            }
                        }
                    }
                    // Fetch a specific course
                    else {
                        $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname $like_operator :course";
                        $params = ['code' => $code, 'course' => $course];
                        $result = $DB->get_record_sql($query, $params);
                        if ($result) { // Check if result exists before accessing properties
                            $course_add = new stdClass();
                            $course_add->name = $result->shortname;
                            $course_add->courseid = $result->id;
                            $course_add->coursestats_category_id = $id;
                            $DB->insert_record('report_coursestatsv2_course', $course_add);
                        }
                        if($filhas){
                            $query = "SELECT id FROM {course_categories} WHERE visible = 1 and parent = :code";
                            $params = ['code' => $code];
                            $cat_ids = $DB->get_records_sql($query, $params);
                            foreach($cat_ids as $cat_id){
                                $query = "SELECT * FROM {course} WHERE visible = 1 and category = :cat and shortname $like_operator :course";
                                $params = ['cat' => $cat_id->id, 'course' => $course];
                                $result = $DB->get_record_sql($query, $params);
                                if($result) {
                                    $course_add = new stdClass();
                                    $course_add->name = $result->shortname;
                                    $course_add->courseid = $result->id;
                                    $course_add->coursestats_category_id = $id;
                                    $DB->insert_record('report_coursestatsv2_course', $course_add);
                                }
                            }
                        }
                    }
                }
            }
            // Perform the same actions as when there are multiple filter configurations but without needing to iterate through an array
            else {
                if (strpos($filters, '%') !== false) {
                    $course = $filters;
                    $course = trim($course);
                    if (strpos($filters, '%') === 0 && strrpos($filters, '%') === strlen($filters) - 1) {
                        $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname $like_operator :course";
                        $params = ['code' => $code, 'course' => $course];
                        $results = $DB->get_records_sql($query, $params);
                        foreach ($results as $result) {
                            $course_add = new stdClass();
                            $course_add->name = $result->shortname;
                            $course_add->courseid = $result->id;
                            $course_add->coursestats_category_id = $id;
                            $DB->insert_record('report_coursestatsv2_course', $course_add);
                        }
                        if($filhas){
                            $query = "SELECT id FROM {course_categories} WHERE visible = 1 and parent = :code";
                            $params = ['code' => $code];
                            $cat_ids = $DB->get_records_sql($query, $params);
                            foreach($cat_ids as $cat_id){
                                $query = "SELECT * FROM {course} WHERE visible = 1 and category = :cat and shortname $like_operator :course";
                                $params = ['cat' => $cat_id->id, 'course' => $course];
                                $results = $DB->get_records_sql($query, $params);
                                foreach ($results as $result) {
                                    $course_add = new stdClass();
                                    $course_add->name = $result->shortname;
                                    $course_add->courseid = $result->id;
                                    $course_add->coursestats_category_id = $id;
                                    $DB->insert_record('report_coursestatsv2_course', $course_add);
                                }
                            }
                        }
                    } elseif (strpos($filters, '%') === 0) {
                        $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname $like_operator :course";
                        $params = ['code' => $code, 'course' => $course];
                        $results = $DB->get_records_sql($query, $params);
                        foreach ($results as $result) {
                            $course_add = new stdClass();
                            $course_add->name = $result->shortname;
                            $course_add->courseid = $result->id;
                            $course_add->coursestats_category_id = $id;
                            $DB->insert_record('report_coursestatsv2_course', $course_add);
                        }
                        if($filhas){
                            $query = "SELECT id FROM {course_categories} WHERE visible = 1 and parent = :code";
                            $params = ['code' => $code];
                            $cat_ids = $DB->get_records_sql($query, $params);
                            foreach($cat_ids as $cat_id){
                                $query = "SELECT * FROM {course} WHERE visible = 1 and category = :cat and shortname $like_operator :course";
                                $params = ['cat' => $cat_id->id, 'course' => $course];
                                $results = $DB->get_records_sql($query, $params);
                                foreach ($results as $result) {
                                    $course_add = new stdClass();
                                    $course_add->name = $result->shortname;
                                    $course_add->courseid = $result->id;
                                    $course_add->coursestats_category_id = $id;
                                    $DB->insert_record('report_coursestatsv2_course', $course_add);
                                }
                            }
                        }
                    } elseif (strrpos($filters, '%') === strlen($filters) - 1) {
                        $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname $like_operator :course";
                        $params = ['code' => $code, 'course' => $course];
                        $results = $DB->get_records_sql($query, $params);
                        foreach ($results as $result) {
                            $course_add = new stdClass();
                            $course_add->name = $result->shortname;
                            $course_add->courseid = $result->id;
                            $course_add->coursestats_category_id = $id;
                            $DB->insert_record('report_coursestatsv2_course', $course_add);
                        }
                        if($filhas){
                            $query = "SELECT id FROM {course_categories} WHERE visible = 1 and parent = :code";
                            $params = ['code' => $code];
                            $cat_ids = $DB->get_records_sql($query, $params);
                            foreach($cat_ids as $cat_id){
                                $query = "SELECT * FROM {course} WHERE visible = 1 and category = :cat and shortname $like_operator :course";
                                $params = ['cat' => $cat_id->id, 'course' => $course];
                                $results = $DB->get_records_sql($query, $params);
                                foreach ($results as $result) {
                                    $course_add = new stdClass();
                                    $course_add->name = $result->shortname;
                                    $course_add->courseid = $result->id;
                                    $course_add->coursestats_category_id = $id;
                                    $DB->insert_record('report_coursestatsv2_course', $course_add);
                                }
                            }
                        }
                    }
                } else {
                    $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname $like_operator :course";
                    $params = ['code' => $code, 'course' => $filters];
                    $result = $DB->get_record_sql($query, $params);
                    if ($result) { // Check if result exists before accessing properties
                        $course_add = new stdClass();
                        $course_add->name = $result->shortname;
                        $course_add->courseid = $result->id;
                        $course_add->coursestats_category_id = $id;
                        $DB->insert_record('report_coursestatsv2_course', $course_add);
                    }
                    if($filhas){
                        $query = "SELECT id FROM {course_categories} WHERE visible = 1 and parent = :code";
                        $params = ['code' => $code];
                        $cat_ids = $DB->get_records_sql($query, $params);
                        foreach($cat_ids as $cat_id){
                            $query = "SELECT * FROM {course} WHERE visible = 1 and category = :cat and shortname $like_operator :course";
                            $params = ['cat' => $cat_id->id, 'course' => $filters];
                            $result = $DB->get_record_sql($query, $params);
                            if($result) {
                                $course_add = new stdClass();
                                $course_add->name = $result->shortname;
                                $course_add->courseid = $result->id;
                                $course_add->coursestats_category_id = $id;
                                $DB->insert_record('report_coursestatsv2_course', $course_add);
                            }
                        }
                    }
                }
            }
        }
    }
}



function report_coursestats_v2_process_moodle_config()
{
    global $DB; // Access the global database object

    // Query to select all visible course categories
    $query = "SELECT id, name FROM {course_categories} WHERE visible = 1";
    // Execute the query and retrieve the records
    $categories = $DB->get_records_sql($query);

    // Iterate through each category
    foreach ($categories as $category) {
        // Create a new object to hold category information for the report
        $category_add = new stdClass();
        $category_add->name = $category->name; // Assign the name of the category

        // Insert the category into the report_coursestats_categories table and get the new ID
        $id = $DB->insert_record('report_coursestatsv2_cat', $category_add);

        // Query to select all visible courses within the current category
        $query = "SELECT * FROM {course} WHERE visible = 1 and category = :codigo";
        $params = ['codigo' => $category->id]; // Bind the category ID to the query
        // Execute the query to retrieve the courses
        $results = $DB->get_records_sql($query, $params);

        // Iterate through each course in the results
        foreach ($results as $result) {
            // Create a new object to hold course information for the report
            $curse_add = new stdClass();
            $curse_add->name = $result->shortname; // Assign the short name of the course
            $curse_add->courseid = $result->id;    // Assign the id of the course on the original Moodle table
            $curse_add->coursestats_category_id = $id; // Link the course to its corresponding category ID in the report table

            // Insert the course information into the report_coursestatsv2_course table
            $DB->insert_record('report_coursestatsv2_course', $curse_add);
        }
    }
}
