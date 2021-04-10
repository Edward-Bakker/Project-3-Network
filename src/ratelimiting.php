<?php
class Ratelimiting
{
    private $db;
    private $config;
    private $id;

    public function __construct($id)
    {
        $this->db = new Database();
        $config = (object) parse_ini_file('../config.ini', true);
        $this->config = (object) $config->ratelimit;
        $this->id = $id;
    }

    public function getLastConnect()
    {
        $query = "SELECT last_update FROM ratelimit WHERE keyid = ?";
        if ($stmt = $this->db->prepare($query))
        {
            $stmt->bind_param('i', $this->id);

            $stmt->execute();

            $stmt->bind_result($lastUpdate);

            $stmt->store_result();

            $result = null;
            if ($stmt->num_rows() === 1)
            {
                while ($stmt->fetch())
                {
                    $result = $lastUpdate;
                }
            }
            $stmt->close();
        }

        return $result;
    }

    public function setLastConnect()
    {
        $query = "UPDATE ratelimit SET last_update = NOW() WHERE keyid = ?";
        if ($stmt = $this->db->prepare($query))
        {
            $stmt->bind_param('i', $this->id);

            $stmt->execute();

            $stmt->close();
        }
    }

    public function createRatelimit()
    {
        $query = "INSERT INTO ratelimit (keyid) VALUES (?)";
        if ($stmt = $this->db->prepare($query))
        {
            $stmt->bind_param('i', $this->id);

            $stmt->execute();

            $stmt->close();
        }
    }

    public function rateLimit()
    {
        $delaySeconds = $this->config->delay_s;
        if ($this->getLastConnect() === null)
        {
            $this->createRatelimit();
        }
        else
        {
            date_default_timezone_set('Europe/Amsterdam');
            $date = strtotime(date("Y-m-d H:i:s"));
            $lastConnection = strtotime($this->getLastConnect(), $date);

            if (($date - $lastConnection) < $delaySeconds)
            {
                header('HTTP/1.1 429 Request Overflow');
                die();
            }
            else
            {
                $this->setLastConnect();
            }
        }
    }
}
