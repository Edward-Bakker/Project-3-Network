<?php
    class Post {
        private $db;
        private $requestMethod;
        private $function;

        public function __construct($db, $requestMethod, $function)
        {
            $this->db = $db;
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
                        default: $response = $this->notFoundResponse(); break;
                    }
                    break;
                case 'POST':
                    switch ($this->function)
                    {
                        case 'settask': $response = $this->setTask(); break;
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
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(['data' => 'bots']);
            return $response;
        }

        private function getTasks()
        {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(['data' => ['bot1' => 'race', 'bot2' => 'maze'],'status' =>'true','message' => 'Request successful.']);
            return $response;
        }

        private function getTask()
        {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(['data' => 'task']);
            return $response;
        }

        private function setTask()
        {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(['data' => 'set task']);
            return $response;
        }

        private function notFoundResponse()
        {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = null;
            return $response;
        }
    }
