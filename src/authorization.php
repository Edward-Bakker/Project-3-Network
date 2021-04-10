<?php
class Authorization
{
    private $db;
    private $id;
    private $secret;

    public function __construct($id, $secret)
    {
        $this->db = new Database();
        $this->id = $id;
        $this->secret = $secret;
    }

    public function Authenticate()
    {
        $query = "SELECT secret FROM apikeys WHERE id = ?";
        if ($stmt = $this->db->prepare($query))
        {
            $stmt->bind_param('i', $this->id);

            $stmt->execute();

            $stmt->bind_result($secret);

            $stmt->store_result();

            $result = null;
            if ($stmt->num_rows() === 1)
            {
                while ($stmt->fetch())
                {
                    $result = $secret;
                }
            }
            $stmt->close();
        }
        $this->db->close();

        if ($result !== null)
        {
            if ($this->secret === $secret)
            {
                return true;
            }
        }
        return false;
    }

    public function getBotId()
    {
        $query = "SELECT botid FROM apikeys WHERE id = ?";
        if ($stmt = $this->db->prepare($query))
        {
            $stmt->bind_param('i', $this->id);

            $stmt->execute();

            $stmt->bind_result($botid);

            $stmt->store_result();

            $result = null;
            if ($stmt->num_rows() === 1)
            {
                while ($stmt->fetch())
                {
                    $result = $botid;
                }
            }
            $stmt->close();
        }
        $this->db->close();
        return $result;
    }

    public function isAdmin()
    {
        $query = "SELECT admin FROM apikeys WHERE id = ?";
        if ($stmt = $this->db->prepare($query))
        {
            $stmt->bind_param('i', $this->id);

            $stmt->execute();

            $stmt->bind_result($admin);

            $stmt->store_result();

            $result = null;
            if ($stmt->num_rows() === 1)
            {
                while ($stmt->fetch())
                {
                    $result = $admin;
                }
            }
            $stmt->close();
        }
        $this->db->close();

        if ($result !== null)
        {
            if ($admin === '1')
            {
                return true;
            }
        }
        return false;
    }
}
