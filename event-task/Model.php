<?php

abstract class AbstractController
{
    abstract public function insert($file);
    abstract public function fetch();
    abstract public function eraseAllData();
}

class Model extends AbstractController
{
    private $server   = "localhost";
    private $userName = "root";
    private $password = '';
    private $db       = "task";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new mysqli($this->server, $this->userName, $this->password, $this->db);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

	public function insert($file)
{
    // var_dump($file);
    $jsonFilePath = $file['tmp_name'];

    if (!file_exists($jsonFilePath)) {
        die('File not found: ' . $jsonFilePath);
    }

    $jsonData = file_get_contents($jsonFilePath);
    $data = json_decode($jsonData, true);
    //var_dump($data);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        die('Error decoding JSON: ' . json_last_error_msg());
    }

    foreach ($data as $response) {
        $eventId = $response['event_id'];
        $eventName = $this->conn->real_escape_string($response['event_name']);
        $eventDate = $response['event_date'];
        $employeeName = $this->conn->real_escape_string($response['employee_name']);
        $employeeMail = $response['employee_mail'];
        $version = $response['version'];
        $participationFee = $response['participation_fee'];

        // Insert event if not exists
        $checkEvent = "SELECT event_id FROM events WHERE event_id = '$eventId'";
        $result = $this->conn->query($checkEvent);

        if (!$result) {
            die("Error checking event existence: " . $this->conn->error);
        }

        if ($result->num_rows == 0) {
            $eventInsertQuery = "INSERT INTO events (event_id, event_name, event_date) VALUES ('$eventId', '$eventName', '$eventDate')";
            if (!$this->conn->query($eventInsertQuery)) {
                die("Error inserting event: " . $this->conn->error);
            }
        }

        // Insert participation
        $insertParticipation = "INSERT INTO event_participations (event_id, employee_name, employee_mail, version, participation_fee) VALUES ('$eventId', '$employeeName', '$employeeMail', '$version', '$participationFee')";
        if (!$this->conn->query($insertParticipation)) {
            die("Error inserting participation: " . $this->conn->error);
        }
    }

    echo "Data inserted successfully.";
}

	

public function fetch()
{
    $eventName = isset($_GET['event_name']) ? $this->conn->real_escape_string($_GET['event_name']) : '';
    $employeeName = isset($_GET['employee_name']) ? $this->conn->real_escape_string($_GET['employee_name']) : '';
    $eventDate = isset($_GET['event_date']) ? $this->conn->real_escape_string($_GET['event_date']) : '';

    $query = "
        SELECT 
            e.event_name, 
            e.event_date, 
            p.employee_name, 
            p.employee_mail, 
            p.participation_fee, 
            p.version 
        FROM 
            events e
        JOIN 
            event_participations p 
        ON 
            e.event_id = p.event_id
        WHERE 
            e.event_name LIKE '%$eventName%'
            AND p.employee_name LIKE '%$employeeName%'
            AND (e.event_date LIKE '%$eventDate%' OR '$eventDate' = '')
        ORDER BY 
            e.event_date, e.event_name, p.employee_name
    ";

    $result = $this->conn->query($query);

    if (!$result) {
        die("Error fetching data: " . $this->conn->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

public function eraseAllData() {
    $this->conn->query("TRUNCATE TABLE events");
    $this->conn->query("TRUNCATE TABLE event_participations");
}



}
