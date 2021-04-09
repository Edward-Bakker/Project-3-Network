<?php
    class Post {
        private $db;
        private $requestMethod;
        private $function;

        public function __construct($requestMethod, $function)
        {
            $this->db = new Database();
            $this->requestMethod = $requestMethod;
            $this->function = $function;
        }

        public function processRequest()
        {
            switch ($this->requestMethod)
            {
                case 'GET':
                    switch ($this->function)
                    {
                        case 'bots': $response = $this->getBots(); break;
                        case 'tasks': $response = $this->getTasks(); break;
                        case 'task': $response = $this->getTask(); break;
                        case 'name': $response = $this->getName(); break;
                        case 'data': $response = $this->getData(); break;
                        default: $response = $this->notFoundResponse(); break;
                    }
                    break;
                case 'POST':
                    switch ($this->function)
                    {
                        case 'settask': $response = $this->setTask(); break;
                        case 'setname': $response = $this->setName(); break;
                        case 'setdata': $response = $this->setData(); break;
                        default: $response = $this->notFoundResponse(); break;
                    }
                    break;
                default:
                    $response = $this->notFoundResponse();
                    break;
            }
            header($response['status_code_header']);
            if($response['body'])
            {
                echo $response['body'];
            }
        }

        private function getBots()
        {
            $query = "SELECT * FROM battlebots";
            if($stmt = $this->db->prepare($query))
            {
                $stmt->execute();

                $result = $stmt->get_result();
                    $responseEntry = array();
                    while ($row = $result->fetch_assoc())
                    {
                        array_push($responseEntry, array($row['id']=>['name' => $row['name'], 'task' => $row['task'], 'data' => $row['data'], 'insert_time' => $row['insert_time'], 'last_update' => $row['last_update']]));
                    }

                        if($result !== null)
                        {

                            $response['status_code_header'] = 'HTTP/1.1 200 OK';
                            $response['body'] = json_encode(['data' => $responseEntry,'status' => true,'message' => 'Request successful.']);
                        }
                        else
                        {
                            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
                            $response['body'] = json_encode(['status' => false,'message' => 'Request failed.']);
                        }

            }
            $this->db->close();
            return $response;
        }

        private function getTasks()
        {
            $query = "SELECT * FROM battlebots";
            if($stmt = $this->db->prepare($query))
            {
                $stmt->execute();

                $result = $stmt->get_result();
                $responseEntry = array();
                while ($row = $result->fetch_assoc())
                {
                    array_push($responseEntry, array($row['name']=>$row['task']));
                }
                $stmt->close();

                if($result !== null)
                {
                    $response['status_code_header'] = 'HTTP/1.1 200 OK';
                    $response['body'] = json_encode(['data' => $responseEntry,'status' => true,'message' => 'Request successful.']);
                }
                else
                {
                    $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
                    $response['body'] = json_encode(['status' => false,'message' => 'Request failed.']);
                }
            }
            $this->db->close();
            return $response;
        }

        private function getTask()
        {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $query = "SELECT task FROM battlebots WHERE id = ?";
            if($stmt = $this->db->prepare($query))
            {
                $stmt->bind_param('i', $id);

                $stmt->execute();

                $stmt->bind_result($task);

                $stmt->store_result();

                $result = null;
                if($stmt->num_rows() === 1)
                {
                    while($stmt->fetch())
                    {
                        $result = $task;
                    }
                }

                $stmt->close();
            }
            $this->db->close();

            if($result !== null)
            {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(['data' => ['task' => $result],'status' => true,'message' => 'Request successful.']);
            }
            else
            {
                $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
                $response['body'] = json_encode(['status' => false,'message' => 'Request failed.']);
            }
            return $response;
        }

        private function getName()
        {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $query = "SELECT `name` FROM battlebots WHERE id = ?";
            if($stmt = $this->db->prepare($query))
            {
                $stmt->bind_param('i', $id);

                $stmt->execute();

                $stmt->bind_result($name);

                $stmt->store_result();

                $result = null;
                if($stmt->num_rows() === 1)
                {
                    while($stmt->fetch())
                    {
                        $result = $name;
                    }
                }

                $stmt->close();
            }
            $this->db->close();

            if($result !== null)
            {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(['data' => ['name' => $result],'status' => true,'message' => 'Request successful.']);
            }
            else
            {
                $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
                $response['body'] = json_encode(['status' => false,'message' => 'Request failed.']);
            }
            return $response;
        }

        private function getData()
        {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $query = "SELECT `data` FROM battlebots WHERE id = ?";
            if($stmt = $this->db->prepare($query))
            {
                $stmt->bind_param('i', $id);

                $stmt->execute();

                $stmt->bind_result($data);

                $stmt->store_result();

                $result = null;
                if($stmt->num_rows() === 1)
                {
                    while($stmt->fetch())
                    {
                        $result = $data;
                    }
                }

                $stmt->close();
            }
            $this->db->close();

            if($result !== null)
            {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(['data' => ['data' => $result],'status' => true,'message' => 'Request successful.']);
            }
            else
            {
                $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
                $response['body'] = json_encode(['status' => false,'message' => 'Request failed.']);
            }
            return $response;
        }

        private function setTask()
        {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $task = filter_input(INPUT_POST, 'task', FILTER_SANITIZE_STRING);
            $result = false;
            $query = "UPDATE battlebots SET task = ? WHERE id = ?";
            if($stmt = $this->db->prepare($query))
            {
                $stmt->bind_param('si', $task, $id);

                $stmt->execute();

                $result = true;

                $stmt->close();
            }
            $this->db->close();

            if($result)
            {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(['data' => ['data' => $result],'status' => true,'message' => 'Task change successful.']);
            }
            else
            {
                $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
                $response['body'] = json_encode(['status' => false,'message' => 'Task change failed.']);
            }
            return $response;
        }

        private function setName()
        {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $result = false;
            $query = "UPDATE battlebots SET name = ? WHERE id = ?";
            if($stmt = $this->db->prepare($query))
            {
                $stmt->bind_param('si', $name, $id);

                $stmt->execute();

                $result = true;

                $stmt->close();
            }
            $this->db->close();

            if($result)
            {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(['status' => true,'message' => 'Name change successful.']);
            }
            else
            {
                $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
                $response['body'] = json_encode(['status' => false,'message' => 'Name change failed.']);
            }
            return $response;
        }

        private function setData()
        {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
            $result = false;
            $query = "UPDATE battlebots SET data = ? WHERE id = ?";
            if($stmt = $this->db->prepare($query))
            {
                $stmt->bind_param('si', $data, $id);

                $stmt->execute();

                $result = true;

                $stmt->close();
            }
            $this->db->close();

            if($result)
            {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(['status' => true,'message' => 'Data change successful.']);
            }
            else
            {
                $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
                $response['body'] = json_encode(['status' => false,'message' => 'Data change failed.']);
            }
            return $response;
        }

        private function notFoundResponse()
        {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = null;
            return $response;
        }
    }
