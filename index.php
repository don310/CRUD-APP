<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize insert status
$insert = false;
$update = false;
$delete = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(isset($_POST['delete'])){
      $sno = $_POST['delete'];
      $delete = true;
      $sql = "DELETE FROM `notes` WHERE `sno` = '$sno'";
      $result = mysqli_query($conn, $sql);
  } else {
      if (isset($_POST['snoEdit'])) {
          // Update the record
          $sno = $_POST["snoEdit"];
          $title = $_POST["titleEdit"];
          $description = $_POST["descriptionEdit"];

          $sql = "UPDATE `notes` SET `title` = '" . mysqli_real_escape_string($conn, $title) . "', `description` = '" . mysqli_real_escape_string($conn, $description) . "' WHERE `notes`.`sno` = $sno";
          $result = mysqli_query($conn, $sql);
          if ($result) {
              $update = true;
          } else {
              echo "We could not update the record successfully";
          }
      } else {
          $title = $_POST["title"];
          $description = $_POST["description"];

          $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('" . mysqli_real_escape_string($conn, $title) . "', '" . mysqli_real_escape_string($conn, $description) . "')";
          $result = mysqli_query($conn, $sql);

          if ($result) {
              $insert = true;
          } else {
              echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }
      }
  }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css//jquery.dataTables.min.css">

    <title>iNotes - Notes taking made easy</title>

</head>

<body>
    <!-- Edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" action="/CRUD/index.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="snoEdit" name="snoEdit">
                        <div class="form-group">
                            <label for="titleEdit">Note Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp" />
                        </div>
                        <div class="form-group">
                            <label for="descriptionEdit">Note Description</label>
                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">iNotes</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact Us</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" />
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <?php
    if ($insert) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your notes has been inserted successfully.
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
      </button>
    </div>";
    }
    ?>

    <?php
    if ($update) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your notes has been updated successfully.
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
      </button>
    </div>";
    }
    ?>

    <?php
    if ($delete) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your notes has been deleted successfully.
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
      </button>
    </div>";
    }
    ?>

    <div class="container my-4">
        <h2>Add a Note</h2>
        <form action="/CRUD/index.php" method="POST">
            <div class="form-group">
                <label for="title">Note Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp" />
            </div>
            <div class="form-group">
                <label for="description">Note Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>

    <div class="container my-4">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = mysqli_connect($servername, $username, $password, $database);
                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $sql);
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno = $sno + 1;
                    echo "<tr>
                    <th scope='row'>" . $sno . "</th>
                    <td>" . $row['title'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td> 
                        <button class='edit btn btn-sm btn-primary' data-toggle='modal' data-target='#editModal' data-sno='" . $row['sno'] . "'>Edit</button> 
                        <form action='/CRUD/index.php' method='POST'>
                            <input type='hidden' name='delete' value='" . $row['sno'] . "'>
                            <button type='submit' class='delete btn btn-sm btn-primary'>Delete</button>
                        </form>
                    </td>
                </tr>";
                }
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
    <hr>

    <!-- jQuery and other scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        // Edit and delete button handling
        $(document).on('click', '.edit', function() {
            var sno = $(this).data('sno');
            var title = $(this).closest('tr').find('td:eq(0)').text();
            var description = $(this).closest('tr').find('td:eq(1)').text();
            $('#snoEdit').val(sno);
            $('#titleEdit').val(title);
            $('#descriptionEdit').val(description);
        });

        $(document).on('click', '.delete', function() {
            var sno = $(this).data('sno');
            if (confirm("Are you sure you want to delete this note?")) {
                window.location = '/CRUD/index.php?delete=' + sno;
            }
        });
    </script>
</body>

</html>
