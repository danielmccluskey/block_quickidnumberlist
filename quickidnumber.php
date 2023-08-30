<?php
//Define the global $CFG variable
require_once('../../config.php');
require_login();

//Parameters idnumber
$idnumber = required_param('idnumber', PARAM_TEXT);
//Create a new page
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/blocks/quickidnumberlist/quickidnumber.php');
$PAGE->set_title('Quick ID Number');
$PAGE->set_heading('Quick ID Number');
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add('Quick ID Number');
echo $OUTPUT->header();
echo '<p>Search for an Assignment by ID Number</p>';

echo '<p>You searched for: ' . $idnumber . '<br></p>';

//Make sure idnumber is not empty
if (empty($idnumber)) {
    echo 'Please enter an ID Number';
}
else
{
    if(isloggedin() && has_capability('block/quickidnumberlist:use', context_system::instance()) )
    {
        //Define the global $DB variable
        global $DB;

        //Define the global $PAGE variable
        global $PAGE;

        //Define the global $OUTPUT variable
        global $OUTPUT;
        //Find the course_modules that have the idnumber that are LIKE the search term
        $sql = "SELECT * FROM {course_modules} WHERE idnumber LIKE '%" . $idnumber . "%'";
        $course_modules = $DB->get_records_sql($sql);

        //If there are no results, echo that there are no results
        if (empty($course_modules)) {
            echo 'No results found';
            die();
        }

        //Get list of Modules from mdl_modules
        $sql = "SELECT * FROM {modules}";
        $modules = $DB->get_records_sql($sql);


        //Create a list to display the results and use anchor tags to link to the course_modules
        $list = '<ul>';
        foreach ($course_modules as $course_module) {
            //Find the course_module->module in the modules table to get the name of the module
            foreach ($modules as $module) {
                if ($module->id == $course_module->module) {
                    $module_name = $module->name;
                }
            }
            //List element with link to the course module directly e.g. http://localhost/moodle/mod/quiz/view.php?id=2
            $list .= '<li><a href="' . $CFG->wwwroot . '/mod/' . $module_name . '/view.php?id=' . $course_module->id . '">' . $course_module->idnumber . '</a></li>';
        }

        //Close the list
        $list .= '</ul>';
        echo $list;
    }
    else
    {
        echo 'You do not have permission to use this feature';
    }
}

echo $OUTPUT->footer();



