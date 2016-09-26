<?php
    class Student
    {
        private $id;
        private $name;
        private $gpa;

        function __construct($name, $gpa, $id = null)
        {
            $this->name = $name;
            $this->gpa = $gpa;
            $this->id = $id;
        }
//---static functions---
        static function getAll()
        {
            $all_students = array();
            $students= $GLOBALS['DB']->query("SELECT * FROM students;");
            foreach($students as $student) {
                $id = $student['id'];
                $name = $student['name'];
                $gpa = $student['gpa'];
                $new_student = new Student($name, $gpa, $id);
                array_push($all_students, $new_student);
            }
            return $all_students;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM students;");
        }

        static function find($search_id)
        {
            $students = Student::getAll();
            foreach($students as $student) {
                if ($student->getId() == $search_id) {
                    return $student;
                }
            }
        }

//---functions---
        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO students (name, gpa) VALUES ('{$this->name}', {$this->gpa});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM students WHERE id = {$this->id};");
        }

        function addTeacher($teacher)
        {
            $GLOBALS['DB']->exec("INSERT INTO teachers_students (teacher_id, student_id) VALUES ({$teacher->getId()}, {$this->id}); ");
        }

        function deleteTeacher($teacher)
        {
            $GLOBALS['DB']->exec("DELETE FROM teachers_students WHERE student_id ={$this->id} AND teacher_id = {$teacher->getId()};");
        }

        function getTeacherList()
        {
            $teachers = array();
            $query = $GLOBALS['DB']->query("SELECT teacher_id FROM teachers_students WHERE student_id = {$this->id};");
            $teacher_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($teacher_ids as $teacher_id) {
                $new_teacher = TEACHER::find($teacher_id['teacher_id']);
                array_push($teachers, $new_teacher);
            }
            return $teachers;
        }
//---getters and setter---
        function getName()
        {
            return $this->name;
        }

        function getSubject()
        {
            return $this->gpa;
        }

        function getId()
        {
            return $this->id;
        }

        function setName($name)
        {
            $this->name = $name;
        }

        function setSubject($gpa)
        {
            $this->gpa = $gpa;
        }
    }
?>
