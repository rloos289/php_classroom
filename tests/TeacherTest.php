<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Teacher.php";
    require_once "src/Student.php";

    //Epicodus
    $server = 'mysql:host=localhost;dbname=school_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    //home mac
    // $server = 'mysql:host=localhost:8889;dbname=best_restaurants';
    // $username = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $username, $password);

    class TeacherTest extends PHPUnit_Framework_TestCase

    //run test in terminal: ./vendor/bin/phpunit tests

    //on Mac: run: export PATH=$PATH:./vendor/bin
    //then run phpunit tests

    {
        protected function tearDown()
        {
            Teacher::deleteAll();
            Student::deleteAll();

        }

        function test_save()
        {
            $name = "Mr. Garrison";
            $subject = "Math";
            $test_teacher = new Teacher ($name, $subject);
            $test_teacher->save();

            $result = Teacher::getAll();

            $this->assertEquals([$test_teacher],$result);
        }

        function test_getAll()
        {
            $name1 = "Mr. Garrison";
            $subject1 = "Math";
            $test_teacher1 = new Teacher ($name1, $subject1);
            $test_teacher1->save();
            $name2 = "Mr. Mackey";
            $subject2 = "Counseling";
            $test_teacher2 = new Teacher ($name2, $subject2);
            $test_teacher2->save();

            $result = Teacher::getAll();

            $this->assertEquals([$test_teacher1, $test_teacher2], $result);
        }

        function test_deleteAll()
        {
            $name1 = "Mr. Garrison";
            $subject1 = "Math";
            $test_teacher1 = new Teacher ($name1, $subject1);
            $test_teacher1->save();
            $name2 = "Mr. Mackey";
            $subject2 = "Counseling";
            $test_teacher2 = new Teacher ($name2, $subject2);
            $test_teacher2->save();

            Teacher::deleteAll();
            $result = Teacher::getAll();


            $this->assertEquals([], $result);
        }

        function test_delete()
        {
            $name1 = "Mr. Garrison";
            $subject1 = "Math";
            $test_teacher1 = new Teacher ($name1, $subject1);
            $test_teacher1->save();
            $name2 = "Mr. Mackey";
            $subject2 = "Counseling";
            $test_teacher2 = new Teacher ($name2, $subject2);
            $test_teacher2->save();

            $test_teacher1->delete();
            $result = Teacher::getAll();


            $this->assertEquals([$test_teacher2], $result);
        }

        function test_find()
        {
            $name1 = "Mr. Garrison";
            $subject1 = "Math";
            $test_teacher1 = new Teacher ($name1, $subject1);
            $test_teacher1->save();
            $name2 = "Mr. Mackey";
            $subject2 = "Counseling";
            $test_teacher2 = new Teacher ($name2, $subject2);
            $test_teacher2->save();

            $result = Teacher::find($test_teacher2->getId());


            $this->assertEquals($test_teacher2, $result);
        }

        function test_addStudent()
        {
            $student_name = "Billy";
            $gpa = 3.1;
            $test_student = new Student($student_name, $gpa);
            $test_student->save();
            $teacher_name = "Mr. Garrison";
            $subject = "math";
            $test_teacher = new Teacher($teacher_name, $subject);
            $test_teacher->save();

            $test_teacher->addStudent($test_student);
            $result = $test_teacher->getStudentList();

            $this->assertEquals([$test_student],$result);
        }

        function test_deleteStudent()
        {
            $student_name = "Billy";
            $gpa = 3.1;
            $test_student = new Student($student_name, $gpa);
            $test_student->save();
            $teacher_name = "Mr. Garrison";
            $subject = "math";
            $test_teacher = new Teacher($teacher_name, $subject);
            $test_teacher->save();

            $test_teacher->addStudent($test_student);
            $test_teacher->deleteStudent($test_student);
            $result = $test_teacher->getStudentList();

            $this->assertEquals([],$result);
        }
    }

      // Testcode example
      //  function test_makeTitleCase_oneWord()
      //  {
      //      //Arrange
      //      $test_TitleCaseGenerator = new Template;
      //      $input = "beowulf";
       //
      //      //Act
      //      $result = $test_TitleCaseGenerator->testTemplate($input);
       //
      //      //Assert
      //      $this->assertEquals("Beowulf", $result);
      //  }


 ?>
