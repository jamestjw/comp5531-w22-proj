<?php
require "../modules/models/meeting.php";
require_once "../common.php";

ensure_logged_in();

try {
    $planned_meetings = Meeting::where(array('has_passed' => false));
    $past_meetings = Meeting::where(array('has_passed' => true));
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php include "templates/header.php"; ?>

<h2>Planned Meetings</h2>
<?php
if ($planned_meetings && count($planned_meetings)) { ?>
        

        <p>
        <table style="width:50%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Agenda</th>
                    <th>Date of meeting</th>
                    <th>Time of meeting</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($planned_meetings as $row) { ?>
            <tr>
                <td><?php echo escape($row->id); ?></td>
                <td><a href="meeting.php?id=<?php echo $row->id; ?>"><?php echo escape($row->title); ?></a></td>
                <td><?php echo escape($row->agenda); ?></td>
                <td><?php echo escape($row->planned_date); ?></td>
                <td><?php echo escape($row->planned_time); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>   
        <?php } ?>
            </tbody>
        </table>
        </p>
<?php } else { ?>
    <blockquote>No meetings planned.</blockquote>
<?php }?>



<h2>Passed Meetings</h2>
<?php
if ($past_meetings && count($past_meetings)) {?>


        <p>
            <table style="width:50%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Agenda</th>
                        <th>Date of meeting</th>
                        <th>Started </th>
                        <th>Ended </th>
                        <th>Meeting minutes</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
            <?php foreach ($past_meetings as $row) { ?>
                <tr>
                    <td><?php echo escape($row->id); ?></td>
                    <td><?php echo escape($row->title); ?></a></td>
                    <td><?php echo escape($row->agenda); ?></td>
                    <td><?php echo escape($row->planned_date); ?></td>
                    <td><?php echo escape($row->start_at); ?></td>
                    <td><?php echo escape($row->end_at); ?></td>
                    <td><?php echo nl2br(escape($row->minutes)); ?></td>
                    <td><?php echo escape($row->created_at);  ?> </td>
                </tr>  
                </tbody>
            </table>
        </p>
            <?php } ?>
<?php } else {?>
    <blockquote>No past meetings</blockquote>
<?php }?>



<p><a href="create_meeting.php">Create new meeting</a></p>

<?php include "templates/footer.php"; ?>