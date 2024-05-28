<?php
include "database/connection.php";
session_name("admin_session");
session_start();

//total customers
$query = "SELECT COUNT(*) AS total_customers FROM insurance1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_customers = $row['total_customers'];



// Retrieve data from the database and order by month
$query = "SELECT EntryMonth, COUNT(*) AS TotalCustomers FROM insurance1 GROUP BY EntryMonth ORDER BY EntryMonth ASC";
$result = mysqli_query($conn, $query);

// Initialize arrays to store labels and data for the chart
$months = [];
$customerCounts = [];

// Extract data from the result set and populate the arrays
while ($row = mysqli_fetch_assoc($result)) {
    $months[] = $row['EntryMonth'];
    $customerCounts[] = $row['TotalCustomers'];
}
 //PHP code to fetch the total count of claims
$query = "SELECT COUNT(*) AS total_claims FROM claims";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_claims = $row['total_claims'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "sidebar.php";?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-40">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Customers</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_customers; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       

                    

                        <!-- Pending Requests Card Example -->
                     <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Claims Made</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $total_claims; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    <!-- Content Row -->
                    
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
<div class="row">
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Customers Overview</h6>
                                    <div class="dropdown no-arrow">
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <canvas id="customerChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Chart.js -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <script>
        $(document).ready(function () {
            // Bar Chart Example
            var ctx = document.getElementById("customerChart");
            var customerChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($months); ?>,
                    datasets: [{
                        label: 'Number of Customers',
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        hoverBackgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        data: <?php echo json_encode($customerCounts); ?>
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Number of Customers'
                            }
                        }],
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'Month'
                            }
                        }]
                    },
                    legend: {
                        display: false
                    }
                }
            });
        });
    </script>
</body>

<style>
    #wrapper {
        min-width: 100%;
        display: flex;
    }
</style>

</html>