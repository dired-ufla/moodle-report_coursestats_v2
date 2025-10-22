[Back](../README.md)

# Using the configuration file

This tutorial presents a practical example of how to use the configuration file for the "CourseStats" plugin. The content is also available in format [video](https://www.youtube.com/watch?v=db7qCcLRKmI).

## 1. Introduction
- The configuration file allows you to customize the categories and courses displayed in the "CourseStats" plugin’s statistical report, without changing the original organization of Moodle. This guide details the steps to efficiently create and configure the file.

## 2. Identifying a Moodle Category ID
- To identify the ID of a category in Moodle:
    - Go to the **Courses and Categories** menu.
    - Click on the desired category and check the category ID displayed in your browser’s address bar (Figure 1).

![Identifying a category ID in Moodle](../images/tut4-1.png)

*Figure 1: Identifying a category ID in Moodle.*

- In the example shown in the video, the following IDs were identified:
    - Category **EF - 1st Year** → ID: 16
    - Category **EF - 2nd Year** → ID: 17
      
## 3. Creating the Configuration File
- Access the plugin through the **Reports** menu and click **[Settings] Course Usage Statistics V2**. 
- In the field designated for the configuration file, insert the text below and click **Save**.


- Example configuration file:

```
EF - Early Years:
16: *
17: * 
```

- In this example, the category **EF - Early Years** was created, which includes all courses from the Moodle categories with IDs `16` and `17`.

- After saving the file, return to the **Reports** menu and click **Course Usage Statistics V2**. The report will be updated according to the categories specified in the configuration file (Figure 2).
  
![Report updated based on the configuration file.](../images/tut4-2.png)

*Figure 2: Report updated based on the configuration file.*

## 4. Working with Many Courses per Category

- As shown in the tutorial ["Using the Plugin for the First Time"](first_usage.md), the **Graduation** category contains more than 1,600 courses. To make the analysis easier, it may be useful to divide the courses in this category into smaller subcategories, allowing for more detailed statistics.

- As discussed in the tutorial  ["Understanding the Anatomy of a Configuration File"](config_file_explanation.md), this can be done using the courses filters available in the plugin.


- Example configuration file:

```
Computer Science:
3: %gcc

Administration:
3: %gae
```

- In the example above:

    - The **Computer Science** category includes courses from category ID 3 (**Graduation**) whose short names end with **gcc**.
    - The **Administration** category includes courses from the same category ID 3, but whose short names end with **gae**.


- When using this file, the report will be generated as illustrated in Figure 3. This makes it possible to obtain statistics on course usage by graduation course at an institution.

![Usage statistics for Computer Science and Administration courses.](../images/tut4-3.png)

*Figure 3: Usage statistics for Computer Science and Administration courses.*


## 5. Final Considerations

- The plugin's configuration file offers robust customization, adapting to the institution's specific needs.

- With it, it is possible to perform different types of analysis, such as:
    - Comparison between **graduation courses** and **postgraduate courses**;
    - Comparison between **humanities courses** and **exact sciences courses**;
    - Between others.
      
- This concludes the tutorial on installing, configuring, and using the "CourseStats" plugin. If you have any questions, we're here to help!
