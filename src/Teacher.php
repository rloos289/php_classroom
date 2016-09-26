<?php
    class Teacher
    {
        private $id;
        private $name;
        private $subject;

        function __construct($name, $subject, $id = null)
        {
            $this->name = $name;
            $this->subject = $subject;
            $this->id = $id;
        }
//---static functions---
        static function getAll()
        {
            $all_teachers = array();
            $teachers= $GLOBALS['DB']->query("SELECT * FROM teachers;");
            foreach($teachers as $teacher) {
                $id = $teacher['id'];
                $name = $teacher['name'];
                $subject = $teacher['subject'];
                $new_teacher = new Teacher($name, $subject, $id);
                array_push($all_teachers, $new_teacher);
            }
            return $all_teachers;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM teachers;");
        }

        static function find($search_id)
        {
            $teachers = Teacher::getAll();
            foreach($teachers as $teacher) {
                if ($teacher->getId() == $search_id) {
                    return $teacher;
                }
            }
        }

//---functions---
        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO teachers (name, subject) VALUES ('{$this->name}', '{$this->subject}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM teachers WHERE id = {$this->id};");
        }

        function addStudent($student)
        {
            $GLOBALS['DB']->exec("INSERT INTO teachers_students (student_id, teacher_id) VALUES ({$student->getId()}, {$this->id});");
        }

        function deleteStudent($student)
        {
            $GLOBALS['DB']->exec("DELETE FROM teachers_students WHERE teacher_id = {$this->id} AND student_id = {$student->getId()};");
        }

        function getStudentList()
        {
            $students = array();
            $query = $GLOBALS['DB']->query("SELECT student_id FROM teachers_students WHERE teacher_id = {$this->getId()};");
            $student_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($student_ids as $student_id) {
                $new_student = Student::find($student_id['student_id']);
                array_push($students, $new_student);
            }
            return $students;
        }
//---getters and setter---
        function getName()
        {
            return $this->name;
        }

        function getSubject()
        {
            return $this->subject;
        }

        function getId()
        {
            return $this->id;
        }

        function setName($name)
        {
            $this->name = $name;
        }

        function setSubject($subject)
        {
            $this->subject = $subject;
        }
    }
?>
