<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";
include "checkSession.php";
include "fetchUserData.php";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Admin - Simulateur</title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="dist/images/logos/favicon.ico" />
    <link id="themeColors" rel="stylesheet" href="dist/css/style.min.css" />
    <link rel="stylesheet" href="dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="dist/libs/sweetalert2/dist/sweetalert2.min.css">

</head>

<body>

    <!-- Preloader -->
    <div class="preloader">
        <img src="dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!-- Preloader -->
    <div class="preloader">
        <img src="dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <?php include 'sidebar.php'; ?>
        <!-- Sidebar End -->
        <!-- Main wrapper -->
        <div class="body-wrapper">
            <!-- Header Start -->
            <?php include 'header.php' ?>
            <!-- Header End -->
            <div class="container-fluid">

                <div class="card bg-light-info shadow-none position-relative overflow-hidden">
                    <div class="card-body px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h4 class="fw-semibold mb-8">Simulateur</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a class="text-muted " href="">Simulateur de profit</a>
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page">Calculer profits</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="col-3">
                                <div class="text-center mb-n5">
                                    <img src="dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between">

                    <div class="d-flex align-items-center">
                        <select name="country" id="countrySelect" class="form-select form-select-lg rounded">
                            <?php
                            $sql_ = "SELECT country, currency FROM simulator_fees";
                            $simulatorFeesData = mysqli_query($conn, $sql_);
                            if ($simulatorFeesData && mysqli_num_rows($simulatorFeesData) > 0) {
                                while ($row = mysqli_fetch_assoc($simulatorFeesData)) {
                                    $country = htmlspecialchars($row['country']);
                                    $currency = htmlspecialchars($row['currency']);
                                    echo "<option value='$country' data-currency='$currency'>$country</option>";
                                }
                            } else {
                                echo "<option value=''>No Countries Found</option>";
                            }
                            ?>
                        </select>


                    </div>



                </div>



                <div class="row mt-4">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body d-flex align-items-center">
                                <span class="badge bg-info" style="margin-right: 10px;"><i
                                        class="ti ti-phone fs-6"></i></span>
                                <h4 class="card-title fs-6">Prix ​​du centre d'appels</h4>

                            </div>
                            <div class="mb-4 p-4 text-center">
                                <form>


                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Lead</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="call_center_lead_fee" disabled>

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Confirmés</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="call_center_confirmation_fee" disabled>

                                        </div>
                                    </div>


                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Livrés
                                            </label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="call_center_delivery_fee" disabled>

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Upsell</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="call_center_upsell_fee" disabled>

                                        </div>
                                    </div>





                                </form>
                            </div>

                            <div class="card-body d-flex align-items-center mt-1">
                                <span class="badge bg-info" style="margin-right: 10px;"><i
                                        class="ti ti-truck-delivery fs-6"></i></span>
                                <h4 class="card-title fs-6">Livraison</h4>

                            </div>
                            <div class="mb-2 p-4 text-center">
                                <form>
                                    <div class="row gx-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Livrés</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="shipping_delivery_fee" disabled>

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Retournés</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="shipping_return_fee" disabled>

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">COD</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="shipping_cod_fee" disabled>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body d-flex align-items-center">
                                <span class="badge bg-info" style="margin-right: 10px;"><i
                                        class="ti ti-packages fs-6"></i></span>
                                <h4 class="card-title fs-6">Produit et coûts</h4>

                            </div>
                            <div class="mb-4 p-4 text-center">
                                <form>
                                    <div class="row gx-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Coût du
                                                produit</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="product_cost">

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Coût de
                                                Upsell</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="upsell_cost">

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">CPL</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded" id="cpl">

                                        </div>
                                    </div>


                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Prix
                                                ​local</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="local_price">

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Prix
                                                USD</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded" disabled
                                                id="usd_price">

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Prix Upsell
                                                ​local</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="local_upsell_price">

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Prix Upsell
                                                USD</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded" disabled
                                                id="usd_upsell_price">

                                        </div>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>


                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body d-flex align-items-center">
                                <span class="badge bg-info" style="margin-right: 10px;"><i
                                        class="ti ti-percentage fs-6"></i></span>
                                <h4 class="card-title fs-6">Taux</h4>

                            </div>
                            <div class="mb-4 p-4 text-center">
                                <form>
                                    <div class="row gx-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Confirmation</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="confirmation_rate">

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Livraison</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="delivery_rate">

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Retour</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded" disabled
                                                id="return_rate">

                                        </div>
                                    </div>


                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">LDR
                                            </label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded" disabled
                                                id="ldr_rate">

                                        </div>
                                    </div>

                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Upsell</label>

                                        </div>
                                        <div class="col-4">
                                            <input type="number" value="0" class="form-control rounded"
                                                id="upsell_rate">

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <button class="btn btn-success" id="calculateButton">Calculer le
                    résultat</button>









            </div>
        </div>
        <div class="dark-transparent sidebartoggler"></div>
        <div class="dark-transparent sidebartoggler"></div>
    </div>
    <!--  Shopping Cart -->

    <!--  Mobilenavbar -->

    <!-- Search Bar -->

    <!-- Customizer -->

    <!-- Customizer -->
    <!-- Import Js Files -->
    <script src="dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- core files -->
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/app.init.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="dist/js/custom.js"></script>
    <!-- current page js files -->
    <script src="dist/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="dist/js/dashboard4.js"></script>
    <script src="dist/js/apps/chat.js"></script>
    <script src="dist/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="dist/js/widgets-charts.js"></script>

    <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="dist/js/forms/sweet-alert.init.js"></script>

    <script src="dist/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="dist/js/datatable/datatable-basic.init.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const countrySelect = document.querySelector('select[name="country"]');
            const inputIds = [
                'shipping_delivery_fee',
                'shipping_return_fee',
                'shipping_cod_fee',
                'call_center_lead_fee',
                'call_center_confirmation_fee',
                'call_center_delivery_fee',
                'call_center_upsell_fee'
            ];

            countrySelect.addEventListener('change', function () {
                const selectedCountry = this.value;

                fetch(`get_country_data.php?country=${encodeURIComponent(selectedCountry)}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data); // Add console log for debugging
                        if (data.error) {
                            console.error(data.error);
                        } else {
                            inputIds.forEach(id => {
                                const input = document.getElementById(id);
                                console.log(id, data[id]); // Add console log for debugging
                                if (id !== 'call_center_product_type') { // Exclude product_type
                                    input.value = data[id];
                                }
                            });
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            if (countrySelect.options.length > 1) {
                countrySelect.dispatchEvent(new Event('change'));
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const localPriceInput = document.getElementById('local_price');
            const usdPriceInput = document.getElementById('usd_price');
            const localUpsellInput = document.getElementById('local_upsell_price');
            const usdUpsellInput = document.getElementById('usd_upsell_price');
            const countrySelect = document.getElementById('countrySelect');

            function convertToUSD(localPrice, usdPriceInput, currencyCode) {
                if (!localPrice) {
                    usdPriceInput.value = 0;
                    return;
                }

                const apiKey = '18c54d36e09846c7ae4fbabe77db88a9'; // Replace with your API key

                fetch(`https://openexchangerates.org/api/latest.json?app_id=${apiKey}`)
                    .then(response => response.json())
                    .then(data => {
                        const exchangeRate = data.rates[currencyCode];
                        if (exchangeRate) {
                            usdPriceInput.value = (parseFloat(localPrice) / exchangeRate).toFixed(2);
                        } else {
                            console.error("Exchange rate not found for currency: " + currencyCode);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching exchange rates:', error);
                    });
            }

            localPriceInput.addEventListener('input', function () {
                fetchCountryDataAndConvert();
            });

            localUpsellInput.addEventListener('input', function () {
                fetchCountryDataAndConvert();
            });

            countrySelect.addEventListener('change', function () {
                fetchCountryDataAndConvert();
            });

            function fetchCountryDataAndConvert() {
                const selectedCountry = countrySelect.value;
                fetch(`get_country_data.php?country=${encodeURIComponent(selectedCountry)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                        } else {
                            if (localPriceInput.value) {
                                convertToUSD(localPriceInput.value, usdPriceInput, data.currency);
                            }
                            if (localUpsellInput.value) {
                                convertToUSD(localUpsellInput.value, usdUpsellInput, data.currency);
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            fetchCountryDataAndConvert(); //load initial data.
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const confirmationRateInput = document.getElementById('confirmation_rate');
            const deliveryRateInput = document.getElementById('delivery_rate');
            const returnRateInput = document.getElementById('return_rate');
            const ldrRateInput = document.getElementById('ldr_rate');

            function calculateRates() {
                const confirmationRate = parseFloat(confirmationRateInput.value) || 0;
                const deliveryRate = parseFloat(deliveryRateInput.value) || 0;

                // Calculate Retour (Return Rate)
                const returnRate = 100 - deliveryRate; // Assuming Retour is 100% - Livraison
                returnRateInput.value = returnRate.toFixed(2);

                // Calculate LDR (Livraison/Delivery Rate)
                const ldrRate = deliveryRate / confirmationRate * 100; // Assuming LDR is Livraison / Confirmation * 100
                ldrRateInput.value = ldrRate.toFixed(2);
            }

            // Add event listeners
            confirmationRateInput.addEventListener('input', calculateRates);
            deliveryRateInput.addEventListener('input', calculateRates);
        });
    </script>




    <script>
        function calculateResults() {
            // Get input values
            // Call Center
            const callCenterLeadFee = parseFloat(document.getElementById('call_center_lead_fee').value) || 0;
            const callCenterConfirmationFee = parseFloat(document.getElementById('call_center_confirmation_fee').value) || 0;
            const callCenterDeliveryFee = parseFloat(document.getElementById('call_center_delivery_fee').value) || 0;
            const callCenterUpsellFee = parseFloat(document.getElementById('call_center_upsell_fee').value) || 0;

            // Shipping
            const shippingDeliveryFee = parseFloat(document.getElementById('shipping_delivery_fee').value) || 0;
            const shippingReturnFee = parseFloat(document.getElementById('shipping_return_fee').value) || 0;
            const shippingCodFee = parseFloat(document.getElementById('shipping_cod_fee').value) || 0;

            // Product and Costs
            const productCost = parseFloat(document.getElementById('product_cost').value) || 0; // Assuming you add this ID
            const upsellCost = parseFloat(document.getElementById('upsell_cost').value) || 0; // Assuming you add this ID
            const cpl = parseFloat(document.getElementById('cpl').value) || 0; // Assuming you add this ID
            const localPrice = parseFloat(document.getElementById('local_price').value) || 0;
            const usdPrice = parseFloat(document.getElementById('usd_price').value) || 0;
            const localUpsellPrice = parseFloat(document.getElementById('local_upsell_price').value) || 0;
            const usdUpsellPrice = parseFloat(document.getElementById('usd_upsell_price').value) || 0;

            // Rates
            const confirmationRate = parseFloat(document.getElementById('confirmation_rate').value) || 0; // Assuming you add this ID
            const deliveryRate = parseFloat(document.getElementById('delivery_rate').value) || 0; // Assuming you add this ID
            const returnRate = parseFloat(document.getElementById('return_rate').value) || 0; // Assuming you add this ID
            const ldrRate = parseFloat(document.getElementById('ldr_rate').value) || 0; // Assuming you add this ID
            const upsellRate = parseFloat(document.getElementById('upsell_rate').value) || 0; // Assuming you add this ID

            // Calculations
            const callCenterTotal = callCenterLeadFee + callCenterConfirmationFee + callCenterDeliveryFee + callCenterUpsellFee;
            const shippingTotal = shippingDeliveryFee + shippingReturnFee + shippingCodFee;
            const overallTotal = callCenterTotal + shippingTotal;
            const totalSales = 0; // You might need to calculate this based on your logic
            const profitPerDelivered = shippingDeliveryFee > 0 ? overallTotal / shippingDeliveryFee : 0;
            const aov = shippingDeliveryFee > 0 ? totalSales / shippingDeliveryFee : 0;
            const totalProfit = totalSales - overallTotal;

            // Display results in SweetAlert
            Swal.fire({
                title: 'Calculation Results',
                html: `
                <p>Call Center Total: $${callCenterTotal}</p>
                <p>Shipping Total: $${shippingTotal}</p>
                <p>Overall Total: $${overallTotal}</p>
                <p>Total Sales: $${totalSales}</p>
                <p>Profit per Delivered: $${profitPerDelivered.toFixed(2)}</p>
                <p>AOV: $${aov.toFixed(2)}</p>
                <p>Total Profit: $${totalProfit}</p>
                `,
                confirmButtonText: 'OK'
            });
        }

        // Add event listener to the button
        // Add event listener to the button
        document.getElementById('calculateButton').addEventListener('click', function () {
            console.log("Button Clicked"); //debugging
            calculateResults();
        });
    </script>


</body>

</html>