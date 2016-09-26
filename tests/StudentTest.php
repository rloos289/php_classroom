<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Student.php";
    require_once "src/Teacher.php";

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

    class StudentTest extends PHPUnit_Framework_TestCase

    //run test in terminal: ./vendor/bin/phpunit tests

    //on Mac: run: export PATH=$PATH:./vendor/bin
    //then run phpunit tests

    {
        protected function tearDown()
        {
            Student::deleteAll();
            Teacher::deleteAll();
        }

        function test_save()
        {
            $name1 = "Billy Everyteen";
            $gpa1 = 3.1;
            $test_student1 = new Student ($name1, $gpa1);
            $test_student1->save();

            $result = Student::getAll();

            $this->assertEquals([$test_student1],$result);
        }

        function test_getAll()
        {
            $name1 = "Billy Everyteen";
            $gpa1 = 3.1;
            $test_student1 = new Student ($name1, $gpa1);
            $test_student1->save();
            $name2 = "Sally Johnson";
            $gpa2 = 1.2;
            $test_student2 = new Student ($name2, $gpa2);
            $test_student2->save();

            $result = Student::getAll();

            $this->assertEquals([$test_student1, $test_student2], $result);
        }

        function test_deleteAll()
        {
            $name1 = "Billy Everyteen";
            $gpa1 = 3.1;
            $test_student1 = new Student ($name1, $gpa1);
            $test_student1->save();
            $name2 = "Sally Johnson";
            $gpa2 = 1.2;
            $test_student2 = new Student ($name2, $gpa2);
            $test_student2->save();

            Student::deleteAll();
            $result = Student::getAll();


            $this->assertEquals([], $result);
        }

        function test_delete()
        {
            $name1 = "Billy Everyteen";
            $gpa1 = 3.1;
            $test_student1 = new Student ($name1, $gpa1);
            $test_student1->save();
            $name2 = "Sally Johnson";
            $gpa2 = 1.2;
            $test_student2 = new Student ($name2, $gpa2);
            $test_student2->save();

            $test_student1->delete();
            $result = Student::getAll();


            $this->assertEquals([$test_student2], $result);
        }

        function test_find()
        {
            $name1 = "Billy Everyteen";
            $gpa1 = 3.1;
            $test_student1 = new Student ($name1, $gpa1);
            $test_student1->save();
            $name2 = "Sally Johnson";
            $gpa2 = 1.2;
            $test_student2 = new Student ($name2, $gpa2);
            $test_student2->save();

            $result = Student::find($test_student2->getId());


            $this->assertEquals($test_student2, $result);
        }

        function test_addTeacher()
        {
            $student_name = "Billy";
            $gpa = 3.1;
            $test_student = new Student($student_name, $gpa);
            $test_student->save();
            $teacher_name = "Mr. Garrison";
            $subject = "math";
            $test_teacher = new Teacher($teacher_name, $subject);
            $test_teacher->save();

            $test_student->addTeacher($test_teacher);
            $result = $test_student->getTeacherList();

            $this->assertEquals([$test_teacher], $result);
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
