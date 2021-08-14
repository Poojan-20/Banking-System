<?php
include 'connection.php';   

if(isset($_POST['submit']))
{
    $from = $_GET['Account_no'];
    $to = $_POST['to'];
    $amount = $_POST['amount'];

    $sql = "SELECT * from customer where Account_no=$from";
    $query = mysqli_query($conn,$sql);
    $sql1 = mysqli_fetch_array($query); 

    $sql = "SELECT * from customer where Account_no=$to";
    $query = mysqli_query($conn,$sql);
    $sql2 = mysqli_fetch_array($query);
 
    if (($amount<0) || ($amount == 0) )
    {
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal("Invalid Value","For Amount Of Money To Be Transferred","error");';
        echo '}, 100);</script>';
    }

    else if($amount > $sql1['Balance']) 
    {
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal("Insufficent Balance","Please Check Your Account Balance","error");';
        echo '}, 100);</script>';
    }

    else 
    {
            // deducting transferring amount from sender's account    
            $newbalance = $sql1['Balance'] - $amount;
            $sql = "UPDATE customer set Balance=$newbalance where Account_no=$from";
            mysqli_query($conn,$sql);

            // adding transferred money to receiver's account
            $newbalance = $sql2['Balance'] + $amount;
            $sql = "UPDATE customer set Balance=$newbalance where Account_no=$to";
            mysqli_query($conn,$sql);
                
            $sender = $sql1['Name'];
            $receiver = $sql2['Name'];
            $sql = "INSERT INTO transfer_history(`sender`, `receiver`, `amount`) VALUES ('$sender','$receiver','$amount')";
            $query=mysqli_query($conn,$sql);

            if($query)
            {
                echo '<script>
                setTimeout(function () {
                    swal({
                        title: "Success!",
                        text: "Transaction Successful",
                        type: "success",
                    }, function () {
                        window.location.href = "transfer_history.php";
                    });
                }, 100);
            </script>';
        }
                
                
            $newbalance= 0;
            $amount =0;
        }   
}
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Transfer Money</title>
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.css" rel="stylesheet">

        <!-- custom CSS here -->
        <link href="css/half-slider.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
        <style type="text/css">
            .section_heading {
                text-align: center;
                padding-top: 6px;
                color: white;
            }
            
            .py-2 {
                font-weight: bolder;
                font-size: 15;
                color: white;
                text-align: center;
            }
            
            .button {
                background-color: rgb(16, 119, 236);
                border: none;
                color: white;
                padding: 16px 32px;
                font-weight: bold;
                text-align: center;
                font-size: 16px;
                margin: 4px 2px;
                border-radius: 20px;
                transition: 0.3s;
                display: inline-block;
                text-decoration: none;
                cursor: pointer;
            }
            
            .button:hover {
                background-color: rgb(255, 255, 255);
                color: black;
            }
            
            #back {
                background: url('img/Hexagon.svg') no-repeat center center/cover;
                height: 100vh;
            }
        </style>
    </head>

    <body id="back">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid d-flex">
                <a class="navbar-brand" href="#">Banking system</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav mb-2 mb-lg-0  ">
                        <li class="nav-item px-3 ">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item px-3 ">
                            <a class="nav-link active" aria-current="page" href="customer.php">View customers</a>
                        </li>
                        <li class="nav-item px-3 ">
                            <a class="nav-link active" aria-current="page" href="transfer.php">Transaction History</a>
                        </li>
                        <li class="nav-item px-3 ">
                            <a class="nav-link active" aria-current="page" href="#services">Services</a>
                        </li>
                        <li class="nav-item px-3 ">
                            <a class="nav-link active" aria-current="page" href="#contactus">Contact Us</a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
        <div class="container">
            <br>
            <br>
            <br>
            <h2 class="section_heading"><b>CUSTOMER DETAILS</b>
            </h2>
            <?php
                include 'connection.php';
                $sid=$_GET['Account_no'];
                $sql = "SELECT * FROM  customer where Account_no=$sid";
                $result=mysqli_query($conn,$sql);
                if(!$result)
                {
                    echo "Error : ".$sql."<br>".mysqli_error($conn);
                }
                $rows=mysqli_fetch_assoc($result);
            ?>
                <form method="post" name="tcredit" class="tabletext"><br>
                    <div>
                        <table class="table table-striped table-condensed table-bordered">
                            <tr>
                                <th class="section_heading py-2">Account no</th>
                                <th class="section_heading py-2">Customer Name</th>
                                <th class="section_heading py-2">Phone Number</th>
                                <th class="section_heading py-2">Balance (in Rs.)</th>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <?php echo $rows['Account_no'] ?>
                                </td>
                                <td class="py-2">
                                    <?php echo $rows['Name'] ?>
                                </td>
                                <td class="py-2">
                                    <?php echo $rows['Phone_no'] ?>
                                </td>
                                <td class="py-2">
                                    <?php echo $rows['Balance'] ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br><br><br>
                    <h2 class="section_heading pt-4"><b>TRANSFER MONEY</b>
                    </h2>
                    <label class="section_heading">Transfer to:</label>
                    <select name="to" class="form-control" required>
                <option value="" disabled selected>Select Customer from Drop-down List</option>
                <?php
                include 'connection.php';
                $sid = $_GET['Account_no'];
                $sql = "SELECT * FROM customer where Account_no!=$sid";
                $result=mysqli_query($conn,$sql);
                if(!$result)                          # If none selected
                {
                    echo "Error ".$sql."<br>".mysqli_error($conn);
                }
                while($rows = mysqli_fetch_assoc($result)) {
            ?>
                <option class="table" value="<?php echo $rows['Account_no'];?>">

                    Customer ID:
                    <?php echo $rows['Account_no'] ;?>&nbsp;&nbsp;Customer Name:
                    <?php echo $rows['Name'] ;?>&nbsp;&nbsp; Balance: Rs.
                    <?php echo $rows['Balance'] ;?>

                </option>
                <?php 
                } 
            ?>
                <div>
            </select>
                    <br>
                    <br>
                    <label class="section_heading">Amount of Money(in Rs.):</label>
                    <input type="number" class="form-control" name="amount" placeholder="Enter Amount to be transferred" required>
                    <br><br>
                    <div class="section_heading">
                        <button class="button" name="submit" type="submit" id="myBtn">Transfer Money</button>
                    </div>
                </form>
        </div>

        </div>
        <!-- /.container -->

        <!-- JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js " integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj " crossorigin="anonymous "></script>
    </body>

    </html>