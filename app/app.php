<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Student.php";
    require_once __DIR__."/../src/Teacher.php";

    //Epicodus
    $server = 'mysql:host=localhost;dbname=school';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    //home mac
    // $server = 'mysql:host=localhost:8889;dbname=best_restaurants';
    // $username = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $username, $password);

    // session_start();
    // if (empty($_SESSION['collection'])) {
    //     $_SESSION['collection'] = array();
    // }

    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

//Homepage
    $app->get("/", function() use ($app) {
        // homepage with links to "teachers" and "students"
        return $app['twig']->render("home.html.twig");
    });

//Students page
    $app->get("/students", function() use ($app) {
        // displays all students in the school
        return $app['twig']->render("/students.html.twig", array('students' => Student::getAll()));
    });

    $app->post("/students", function() use ($app) {
        // adds new student to the database
        $name = $_POST['student_name'];
        $gpa = $_POST['gpa'];
        $new_student = new Student ($name, $gpa);
        $new_student->save();
        return $app['twig']->render("/students.html.twig", array('students' => Student::getAll()));
    });

    $app->delete("/students/{student_id}", function($student_id) use ($app) {
        // remove a students from the database
        $student = Student::find($student_id);
        $student->delete();
        return $app['twig']->render("/students.html.twig", array('students' => Student::getAll()));
    });

    // $app->patch("/students", function() use ($app) {
    //     // changing a student in the database
    //     return $app['twig']->render("/students.html.twig", array('students' => Student::getAll()));
    // });

//Teachers page
    $app->get("/teachers", function() use ($app) {
        // displays all teachers in the school
        return $app['twig']->render("/teachers.html.twig", array('teachers' => Teacher::getAll()));
    });

    $app->post("/teachers", function() use ($app) {
        // adds new teacher to the database
        $name = $_POST['teacher_name'];
        $subject = $_POST['subject'];
        $new_teacher = new Teacher($name, $subject);
        $new_teacher->save();
        return $app['twig']->render("/teachers.html.twig", array('teachers' => Teacher::getAll()));
    });

    $app->delete("/teachers/{teacher_id}", function($teacher_id) use ($app) {
        // remove a teachers from the database
        $teacher = Teacher::find($teacher_id);
        $teacher->delete();
        return $app['twig']->render("/teachers.html.twig", array('teachers' => Teacher::getAll()));
    });
    // $app->patch("/teachers", function() use ($app) {
    //     // changing a teacher in the database
    //     return $app['twig']->render("/teachers.html.twig", array('teachers' => Teacher::getAll()));
    // });

// Student (singular) page
    $app->get("/student/{student_id}", function ($student_id) use ($app) {
        // display all teachers linked to an individual student
        $student = Student::find($student_id);
        $teachers = $student->getTeacherList();
        return $app['twig']->render("student.html.twig", array('teachers' => $teachers, 'student' => $student));
    });

    $app->post("/student/{student_id}", function ($student_id) use ($app) {
        // add a teacher relationship to an individual student
        $student = Student::find($student_id);
        $teacher_id = $_POST['teacher_id'];
        $teacher = Teacher::find($teacher_id);
        $student->addTeacher($teacher);
        $teachers = $student->getTeacherList();
        return $app['twig']->render("student.html.twig", array('teachers' => $teachers, 'student' => $student));
    });

    $app->delete("/student/{student_id}", function ($student_id) use ($app) {
        // delete a teacher relationship from an individual student
        $student = Student::find($student_id);
        $teacher_id = $_POST['teacher_id'];
        $teacher = Teacher::find($teacher_id);
        $student->deleteTeacher($teacher);
        $teachers = $student->getTeacherList();
        return $app['twig']->render("student.html.twig", array('teachers' => $teachers, 'student' => $student));
    });

// Teacher (singular) page
    $app->get("/teacher/{teacher_id}", function ($teacher_id) use ($app) {
        // display all students linked to an individual teacher
        $teacher = Teacher::find($teacher_id);
        $students = $teacher->getStudentList();
        return $app['twig']->render("teacher.html.twig", array('teacher' => $teacher, 'students' => $students));
    });

    $app->post("/teacher/{teacher_id}", function ($teacher_id) use ($app) {
        // add a student relationship to an individual teacher
        $teacher = Teacher::find($teacher_id);
        $student_id = $_POST['student_id'];
        $student = Student::find($student_id);
        $teacher->addStudent($student);
        $students = $teacher->getStudentList();
        return $app['twig']->render("teacher.html.twig", array('teacher' => $teacher, 'students' => $students));
        // return $app->redirect('/teacher/' . $teacher_id);
    });

    $app->delete("/teacher/{teacher_id}", function ($teacher_id) use ($app) {
        // delete a student relationship from an individual teacher
        $teacher = Teacher::find($teacher_id);
        $student_id = $_POST['student_id'];
        $student = Student::find($student_id);
        $teacher->deleteStudent($student);
        $students = $teacher->getStudentList();
        return $app['twig']->render("teacher.html.twig", array('teacher' => $teacher, 'students' => $students));
    });

    return $app;
?>
