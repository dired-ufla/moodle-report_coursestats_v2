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

function processCustomConfig($configuration)
{
    // Make the database instance global to use it anywhere in the function
    global $DB;
    global $id;

    // Split the new lines into an array, the explode function is used for this
    $lines = explode("\n", trim($configuration));

    // Variable to store the current category from which courses will be fetched from the database
    $currentCategory = '';

    // foreach to process each of the lines
    foreach ($lines as $line) {
        // The trim function removes spaces from the beginning and end of the line
        $line = trim($line);

        // Check to determine if it is a category or a filter for the courses to be fetched
        if (strpos($line, ':') !== false && trim(substr($line, strpos($line, ':') + 1)) === '') {
            $currentCategory = rtrim($line, ':');
            $category = new stdClass();
            $category->name = $currentCategory;
            $id = $DB->insert_record('report_coursestats_categories', $category);
        } elseif (strpos($line, ':') !== false) {
            // The list function assigns each variable a value from the passed array
            list($code, $filters) = explode(':', $line);
            $code = trim($code);
            $filters = trim($filters);

            // Check if all courses are requested
            if ($filters == '*') {
                // Fetch all courses from the database
                $courses = $DB->get_records('course');
                foreach ($courses as $course) {
                    $course_add = new stdClass();
                    $course_add->name = $course->fullname;
                    $course_add->coursestats_category_id = $id;
                    $DB->insert_record('report_coursestats_courses', $course_add);
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
                            $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname LIKE :course";
                            $params = ['code' => $code, 'course' => $course];
                            $results = $DB->get_records_sql($query, $params);
                            foreach ($results as $result) {
                                $course_add = new stdClass();
                                $course_add->name = $result->fullname;
                                $course_add->coursestats_category_id = $id;
                                $DB->insert_record('report_coursestats_courses', $course_add);
                            }
                            // Fetch courses that end with a specific name
                        } elseif (strpos($course, '%') === 0) {
                            $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname LIKE :course";
                            $params = ['code' => $code, 'course' => $course];
                            $results = $DB->get_records_sql($query, $params);
                            foreach ($results as $result) {
                                $course_add = new stdClass();
                                $course_add->name = $result->fullname;
                                $course_add->coursestats_category_id = $id;
                                $DB->insert_record('report_coursestats_courses', $course_add);
                            }
                            // Fetch courses that start with a specific name
                        } elseif (strrpos($course, '%') === strlen($course) - 1) {
                            $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname LIKE :course";
                            $params = ['code' => $code, 'course' => $course];
                            $results = $DB->get_records_sql($query, $params);
                            foreach ($results as $result) {
                                $course_add = new stdClass();
                                $course_add->name = $result->fullname;
                                $course_add->coursestats_category_id = $id;
                                $DB->insert_record('report_coursestats_courses', $course_add);
                            }
                        }
                    }
                    // Fetch a specific course
                    else {
                        $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname LIKE :course";
                        $params = ['code' => $code, 'course' => $course];
                        $result = $DB->get_record_sql($query, $params);
                        if ($result) { // Check if result exists before accessing properties
                            $course_add = new stdClass();
                            $course_add->name = $result->fullname;
                            $course_add->coursestats_category_id = $id;
                            $DB->insert_record('report_coursestats_courses', $course_add);
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
                        $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname LIKE :course";
                        $params = ['code' => $code, 'course' => $course];
                        $results = $DB->get_records_sql($query, $params);
                        foreach ($results as $result) {
                            $course_add = new stdClass();
                            $course_add->name = $result->fullname;
                            $course_add->coursestats_category_id = $id;
                            $DB->insert_record('report_coursestats_courses', $course_add);
                        }
                    } elseif (strpos($filters, '%') === 0) {
                        $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname LIKE :course";
                        $params = ['code' => $code, 'course' => $course];
                        $results = $DB->get_records_sql($query, $params);
                        foreach ($results as $result) {
                            $course_add = new stdClass();
                            $course_add->name = $result->fullname;
                            $course_add->coursestats_category_id = $id;
                            $DB->insert_record('report_coursestats_courses', $course_add);
                        }
                    } elseif (strrpos($filters, '%') === strlen($filters) - 1) {
                        $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname LIKE :course";
                        $params = ['code' => $code, 'course' => $course];
                        $results = $DB->get_records_sql($query, $params);
                        foreach ($results as $result) {
                            $course_add = new stdClass();
                            $course_add->name = $result->fullname;
                            $course_add->coursestats_category_id = $id;
                            $DB->insert_record('report_coursestats_courses', $course_add);
                        }
                    }
                } else {
                    $query = "SELECT * FROM {course} WHERE visible = 1 and category = :code and shortname LIKE :filters";
                    $params = ['code' => $code, 'filters' => $filters];
                    $result = $DB->get_records_sql($query, $params);
                    if ($result) { // Check if result exists before accessing properties
                        $course_add = new stdClass();
                        $course_add->name = $result->fullname;
                        $course_add->coursestats_category_id = $id;
                        $DB->insert_record('report_coursestats_courses', $course_add);
                    }
                }
            }
        }
    }
}



function processMoodleConfig()
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
        $id = $DB->insert_record('report_coursestats_categories', $category_add);

        // Query to select all visible courses within the current category
        $query = "SELECT * FROM {course} WHERE visible = 1 and category = :codigo";
        $params = ['codigo' => $category->id]; // Bind the category ID to the query
        // Execute the query to retrieve the courses
        $results = $DB->get_records_sql($query, $params);

        // Iterate through each course in the results
        foreach ($results as $result) {
            // Create a new object to hold course information for the report
            $curse_add = new stdClass();
            $curse_add->name = $result->fullname; // Assign the full name of the course
            $curse_add->coursestats_category_id = $id; // Link the course to its corresponding category ID in the report table

            // Insert the course information into the report_coursestats_courses table
            $DB->insert_record('report_coursestats_courses', $curse_add);
        }
    }
}
