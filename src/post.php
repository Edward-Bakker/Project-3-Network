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
                        default: $response = $this->notFoundResponse(); break;
                    }
                    break;
                case 'POST':
                    switch ($this->function)
                    {
                        case 'settask': $response = $this->setTask(); break;
                        case 'setname': $response = $this->setName(); break;
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
                        array_push($responseEntry, array($row['id']=>['name' => $row['name'], 'task' => $row['task'], 'insert_time' => $row['insert_time'], 'last_update' => $row['last_update']]));
                    }

                        if($result !== null)
                        {

                            $response['status_code_header'] = 'HTTP/1.1 200 OK';
                            $response['body'] = json_encode(['data' => [$responseEntry],'status' => true,'message' => 'Request successful.']);
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
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(['data' => ['bot1' => 'race', 'bot2' => 'maze'],'status' => true,'message' => 'Request successful.']);
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
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(['data' => ['name' => 'bot1'],'status' => true,'message' => 'Request successful.']);
            return $response;
        }

        private function setTask()
        {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(['status' => true,'message' => 'Task change successful.']);
            return $response;
        }

        private function setName()
        {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(['status' => true,'message' => 'Name change successful.']);
            return $response;
        }

        private function notFoundResponse()
        {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = null;
            return $response;
        }
    }
