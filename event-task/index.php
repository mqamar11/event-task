<?php

include("./Model.php");
$event = new Model();
$eventsData = $event->fetch();
// var_dump($eventsData);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['erase'])) {
    $event->eraseAllData();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Event Data</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page-header" style="margin-top:5%">
    <h2>Upload Json File</h2>
    <form class="erase-form" method="post" action="">
        <button type="submit" name="erase" onclick="return confirm('Are you sure?')">Erase All Data</button>
    </form>
</div>

<div class="filter-container">
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file_upload">Upload File:</label>
        <input type="file" id="file_upload" name="file_upload" accept=".json" required>
        <button class="submit-button" type="submit">Submit</button>
    </form> 
</div>


<h2>Filter Events</h2>
<form method="get" action="">
    <label for="event_name"><b>Event Name:</b></label>
    <input type="text" id="event_name" name="event_name" value="<?php echo isset($_GET['event_name']) ? htmlspecialchars($_GET['event_name']) : ''; ?>">
    <label for="employee_name"><b>Employee Name:</b></label>
    <input type="text" id="employee_name" name="employee_name" value="<?php echo isset($_GET['employee_name']) ? htmlspecialchars($_GET['employee_name']) : ''; ?>">
    <div>
    <label for="event_date"><b>Event Date:</b></label>
    <input type="date" id="event_date" name="event_date" value="<?php echo isset($_GET['event_date']) ? htmlspecialchars($_GET['event_date']) : ''; ?>">
    <button class="search-button" type="submit">Search</button>
    </div>
   
</form>

<h1>Events Data</h1>
<table id="event_table">
    <thead>
        <tr>
            <th>Event Name</th>
            <th>Employee Name</th>
            <th>Employee Mail</th>
            <th>Event Date</th>
            <th>Version</th>
            <th>Participation Fee</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $totalFee = 0;
     if (!empty($eventsData)){ ?>
            <?php foreach ($eventsData as $data){    
                $totalFee += $data['participation_fee'];
                ?>
            <tr>
                <td><?php echo htmlspecialchars($data['event_name']); ?></td>
                <td><?php echo htmlspecialchars($data['employee_name']); ?></td>
                <td><?php echo htmlspecialchars($data['employee_mail']); ?></td>
                <td><?php echo htmlspecialchars($data['event_date']); ?></td>
                <td><?php echo htmlspecialchars($data['version']); ?></td>
                <td><?php echo htmlspecialchars($data['participation_fee']); ?></td>
            </tr>
            <?php }  ?>
            <tr>
                <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
                <td><?php echo $totalFee; ?></td>
            </tr>

        <?php }else{ ?>
            <tr>
                <td colspan="6">No records found</td>
            </tr>
            
           
        <?php } ?>
    </tbody>
</table>

<script>
    
</script>

</body>
</html>

