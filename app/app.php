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

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

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
        return $app->redirect('/students');
        // return $app['twig']->render("/students.html.twig", array('students' => Student::getAll()));
    });

    $app->delete("/students/{student_id}", function($student_id) use ($app) {
        // remove a students from the database
        $student = Student::find($student_id);
        $student->delete();
        return $app->redirect('/students');
        // return $app['twig']->render("/students.html.twig", array('students' => Student::getAll()));
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
        return $app->redirect('/teachers');
        // return $app['twig']->render("/teachers.html.twig", array('teachers' => Teacher::getAll()));
    });

    $app->delete("/teachers/{teacher_id}", function($teacher_id) use ($app) {
        // remove a teachers from the database
        $teacher = Teacher::find($teacher_id);
        $teacher->delete();
        return $app->redirect('/teachers');
        // return $app['twig']->render("/teachers.html.twig", array('teachers' => Teacher::getAll()));
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
        return $app['twig']->render("student.html.twig", array('teachers' => $teachers, 'student' => $student, 'all_teachers' => Teacher::getAll()));
    });

    $app->post("/student/{student_id}", function ($student_id) use ($app) {
        // add a teacher relationship to an individual student
        $student = Student::find($student_id);
        $teacher_id = $_POST['teacher_id'];
        $teacher = Teacher::find($teacher_id);
        $student->addTeacher($teacher);
        return $app->redirect('/student/' . $student.getId());
        // $teachers = $student->getTeacherList();
        // return $app['twig']->render("student.html.twig", array('teachers' => $teachers, 'student' => $student, 'all_teachers' => Teacher::getAll()));
    });

    $app->delete("/student/{student_id}", function ($student_id) use ($app) {
        // delete a teacher relationship from an individual student
        $student = Student::find($student_id);
        $teacher_id = $_POST['teacher_id'];
        $teacher = Teacher::find($teacher_id);
        $student->deleteTeacher($teacher);
        return $app->redirect('/student/' . $student.getId());
        // $teachers = $student->getTeacherList();
        // return $app['twig']->render("student.html.twig", array('teachers' => $teachers, 'student' => $student, 'all_teachers' => Teacher::getAll()));
    });

// Teacher (singular) page
    $app->get("/teacher/{teacher_id}", function ($teacher_id) use ($app) {
        // display all students linked to an individual teacher
        $teacher = Teacher::find($teacher_id);
        $students = $teacher->getStudentList();
        return $app['twig']->render("teacher.html.twig", array('teacher' => $teacher, 'students' => $students, 'all_students' => Student::getAll()));
    });

    $app->post("/teacher/{teacher_id}", function ($teacher_id) use ($app) {
        // add a student relationship to an individual teacher
        $teacher = Teacher::find($teacher_id);
        $student_id = $_POST['student_id'];
        $student = Student::find($student_id);
        $teacher->addStudent($student);
        return $app->redirect('/teacher/' . $teacher.getId());
        // $students = $teacher->getStudentList();
        // return $app['twig']->render("teacher.html.twig", array('teacher' => $teacher, 'students' => $students, 'all_students' => Student::getAll()));
        // return $app->redirect('/teacher/' . $teacher_id);
    });

    $app->delete("/teacher/{teacher_id}", function ($teacher_id) use ($app) {
        // delete a student relationship from an individual teacher
        $teacher = Teacher::find($teacher_id);
        $student_id = $_POST['student_id'];
        $student = Student::find($student_id);
        $teacher->deleteStudent($student);
        return $app->redirect('/teacher/' . $teacher.getId());
        // $students = $teacher->getStudentList();
        // return $app['twig']->render("teacher.html.twig", array('teacher' => $teacher, 'students' => $students, 'all_students' => Student::getAll()));
    });

    return $app;
?>
