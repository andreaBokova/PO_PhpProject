<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login-form.php");
}
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    echo "User ID not found in the session.";
}
include_once "../parts/head.php";

$topProducts = $db->getTopProducts($userId);
$productsLowInStock = $db->getLowStockProducts($userId);



// Extract labels and sales data from the array
$labels = array_map(function ($product) {
    return $product["name"];
}, $topProducts);

$salesData = array_map(function ($product) {
    return $product["total_quantity_sold"];
}, $topProducts);

// Create an array for JavaScript
$topProductsData = array(
    "labels" => $labels,
    "salesData" => $salesData
);

$topProductsJSON = json_encode($topProductsData);


?>


<!DOCTYPE html>
<html lang="en">

<body id="reportsPage">
    <div class="" id="home">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php include_once "../parts/navigation.php"; ?>
                </div>
            </div>
            <div class="row tm-content-row tm-mt-big">
                <div class="tm-col tm-col-big">
                    <div class="bg-white tm-block h-100">
                        <h2 class="tm-block-title">Top Sales</h2>
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
                <div class="tm-col tm-col-small">
                    <div class="bg-white tm-block h-100">
                        <canvas id="pieChart" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
                <div class="tm-col tm-col-big">
                    <div class="bg-white tm-block h-100">
                        <div class="row">
                            <div class="col-8">
                                <h2 class="tm-block-title d-inline-block">Top 5 Products List</h2>

                            </div>
                            <div class="col-4 text-right">
                                <a href="products.php" class="tm-link-black">View All</a>
                            </div>
                        </div>
                        <ol class="tm-list-group tm-list-group-alternate-color tm-list-group-pad-big">
                            <?php
                            foreach ($topProducts as $topProduct) {
                                echo "<li class='tm-list-group-item'>";
                                echo $topProduct['name'] . " (" . $topProduct['total_quantity_sold'] . " sold)";
                                echo "</li>";
                            }
                            ?>
                        </ol>
                    </div>
                </div>
                <div class="tm-col tm-col-big">
                    <div class="bg-white tm-block h-100">
                        <h2 class="tm-block-title">Calendar</h2>
                        <div id="calendar"></div>
                        <div class="row mt-4">
                            <div class="col-12 text-right">
                                <a href="#" class="tm-link-black">View Schedules</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tm-col tm-col-small">
                    <div class="bg-white tm-block h-100">
                        <h2 class="tm-block-title">Low in stock</h2>
                        <ol class="tm-list-group">

                            <?php
                            foreach ($productsLowInStock as $productLowInStock) {
                                echo "<li class='tm-list-group-item'>" . $productLowInStock['name'] . "</li>";
                            }
                            ?>
                        </ol>
                    </div>
                </div>
            </div>
            <?php include_once "../parts/footer.php"; ?>
        </div>
    </div>
    
    <script src="../js/jquery-3.3.1.min.js"></script>
    <!-- https://jquery.com/download/ -->
    <script src="../js/moment.min.js"></script>
    <!-- https://momentjs.com/ -->
    <script src="../js/utils.js"></script>
    <script src="../js/Chart.min.js"></script>
    <!-- http://www.chartjs.org/docs/latest/ -->
    <script src="../js/fullcalendar.min.js"></script>
    <!-- https://fullcalendar.io/ -->
    <script src="../js/bootstrap.min.js"></script>
    <!-- https://getbootstrap.com/ -->
    <script src="../js/tooplate-scripts.js"></script>
    <script>
        let ctxLine,
            ctxBar,
            ctxPie,
            optionsLine,
            optionsBar,
            optionsPie,
            configLine,
            configBar,
            configPie,
            lineChart;
        barChart, pieChart;

        var topProductsData = <?php echo $topProductsJSON; ?>;


        // DOM is ready
        $(function() {
            updateChartOptions();
            drawBarChart(topProductsData.labels, topProductsData.salesData); // Bar Chart
            drawPieChart(topProductsData.labels, topProductsData.salesData); // Pie Chart
            drawCalendar(); // Calendar

            $(window).resize(function() {
                updateChartOptions();
                updateLineChart();
                updateBarChart();
                reloadPage();
            });
        })
    </script>
</body>

</html>