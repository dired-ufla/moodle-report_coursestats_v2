[Back](../README.md)

# Understanding the anatomy of a configuration file

This tutorial presents the anatomy of the "CourseStats" plugin configuration file. The content is also available in format [video](https://www.youtube.com/watch?v=b8SyizBfEIs).

## 1. What is a configuration file?
- The configuration file is a text document that allows you to organize and customize the categorization of courses within the plugin. It is used to define categories and courses without affecting the existing organization in Moodle.
  
- Each file can contain one or more categories, and each category can be associated with a list of courses.


## 2. Structure of a Configuration File

- The file is composed of categories and their respective course lists.

    - Each category in the configuration file is identified by a name followed by a colon (`:`) at the end. The category name is free and can be defined by the user.

    - On the line below each category is the list of courses belonging to it.

- Each entry in the course list contains:

    - The Moodle category identifier where the course is located, followed by a colon (`:`); and
    - The course short name(s), separated by a comma (`,`). The short name can be entered completely or just as a partial string, as will be explained later.

## 3. Example of a configuration file

- Consider the example below. This file consists of two categories, **Math** and **Portuguese**, and each category contains two courses. For example, **Math** is composed of the courses **1ef-mat** and **2ef-mat**, which come from Moodle category IDs `1` and `2`.

```
Math:
1: 1ef-mat
2: 2ef-mat

Portuguese:
1: 1ef-lp
2: 2ef-lp
```

- The next example presents only one category, **First Year - EF**, which contains two courses, **1ef-mat** and **1ef-lp**, both belonging to the Moodle category ID `1`.
  
```
First Year - EF:
1: 1ef-mat, 1ef-lp
```

## 4. Filtering Courses in a Category

- The plugin allows you to simplify course filtering by using parts of the course's short name. To do this, you can use the percent symbol (`%`) to represent omitted parts of the name.
  
    - To filter all courses in a category that ends with **mat**, use: `%mat`.
    - To filter all courses in a category that starts with **mat**, use: `mat%`.
    - To filter all courses in a category that contain the word **mat**, use: `%mat%`.

-Additionally, to add all courses in a given Moodle category, you can use the asterisk (`*`) symbol.

- Consider the example below. It consists of two categories: **Math** and **General**.

    - The **Math** category includes all courses in Moodle category ID `1` that end with **mat** (e.g., **1ef-mat**, **2ef-mat**). 
    - The **General** category includes all courses in Moodle category ID `1`.

```
Math:
1: %mat

General:
1: *
```

## 5. Conclusion

- In the [next tutorial](config_file_usage.md), a practical example of using the configuration file in a Moodle installation will be presented, allowing you to view the results of this configuration.
