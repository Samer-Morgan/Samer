Api web :<?php
            class Helper
            {


                private $conn;
                public function __construct()
                {
                    createDbConnection();
                }

                function createDbConnection()
                {
                    try {
                        $this->conn = new mysqli("localhost", "root", "", "webphp");
                    } catch (Exception $error) {
                        echo $error->getMessage();
                    }
                }
                function insertNewEmployee($name, $email, $image, $password)
                {
                    try {
                        $current_date = date('Y-m-d H:i:s');
                        $file_link = $this->saveImage($image);
                        $sql = "INSERT INTO Employee (name,email,image,created_at,password )VALUES ('$name','$email','$file_link','$current_date',$password)";
                        $result =  $this->conn->query($sql);
                        if ($result == true) {
                            $this->createResponse(
                                true,
                                $this->createStudentResponse(
                                    $this->conn->insert_id,
                                    $name,
                                    $email,
                                    $file_link,
                                    $current_date,
                                    $password
                                )
                            )
                        } else {
                            $this->createResponse(false, "data has not been inserted");
                        }
                    } catch (Exception $error) {
                        $this->createResponse(false, $error->getMessage());
                    }
                }
                function getAllEmployee()
                {
                    try {
                        $sql = "select * from Employee";
                        $result = $this->conn->query($sql);

                        $count =  $result->num_rows;
                        if ($count > 0) {
                            $all_Employee_array = array();
                            while ($row = $result->fetch_assoc()) {
                                $id = $row["id"];
                                $name = $row["name"];
                                $email = $row["email"];
                                $image = $row["image"];
                                $date = $row["created_at"];
                                $password = $row["password"];

                                $Employee_array = $this->createEmployeeResponse($id, $name, $email, $image, $date, $password);
                                array_push($all_Employee_array, $Employee_array);
                            }
                            $this->createResponse(true, $count, $all_Employee_array);
                        } else {
                            throw  Exception("No Data Found");
                        }
                    } catch (Exception $exception) {
                        $this->createResponse(false, 0, array("error" => $exception->getMessage()));
                    }
                }
                function getEmployeeById($id)
                {
                    $sql = "select * from Employee where id = $id";
                    $result = $this->conn->query($sql);
                    try {
                        if ($result->num_rows == 0) {
                            throw new Exception("there are no Employee with the passed id");
                        } else {
                            $row =   $result->fetch_assoc();
                            $id = $row["id"];
                            $name = $row["name"];
                            $email = $row["email"];
                            $image = $row["image"];
                            $date = $row["created_at"];
                            $password = $row["password"];

                            $Employee_array = $this->createEmployeeResponse($id, $name, $email, $image, $date, $password);
                            $this->createResponse(true, 1, $Employee_array);
                        }
                    } catch (Exception $exception) {
                        http_response_code(400);
                        $this->createResponse(false, 0, array("error" => $exception->getMessage()));
                    }
                }
                function deleteEmployee($id)
                {
                    try {
                        $sql = "delete from Employee where id = $id";
                        $result = $this->conn->query($sql);

                        if (mysqli_affected_rows($this->conn) > 0) {
                            $this->createResponse(true, 1, array("data" => "Employee has been deleted"));
                        } else {
                            throw new Exception("There are no Employee with the passed id");
                        }
                    } catch (Exception $exception) {
                        $this->createResponse(false, 0, array("error" => $exception->getMessage()));
                    }
                }
                function updateEmployee($id, $name, $email, $password)
                {
                    try {
                    } catch (Exception $exception) {
                        $this->createResponse(false, 0, array("error" => $exception->getMessage()));
                    }
                }
                function saveImage($file)
                {
                    $dir_name = "images/";
                    $fullPath = $dir_name . $file["name"];
                    move_uploaded_file($file["tmp_name"], $fullPath);
                    $file_link = "http://localhost/ewb/$fullPath";
                    return $file_link;
                }

                function createResponse($isSuccess, $count, $data)
                {
                    echo json_encode(array(
                        "success" => $isSuccess,
                        "count" => $count,
                        "data" => $data
                    ));
                }
                function createEmployeeResponse($id, $name, $email, $image_url, $created_date, $password)
                {
                    return array(
                        "id" => $id,
                        "name" => $name,
                        "email" => $email,
                        "image" => $image_url,
                        "created_at" => $created_date,
                        "password"  => $password
                    );
                }
            }
            ?>