

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
                <div class="row">
                    <div class="col-lg-4 d-flex">
                        <div class="alert alert-info w-100 d-flex flex-column">
                            <h1 class="fs-8">1</h1>
                            <p class="flex-grow-1">L'utilisateur doit saisir le nombre de publicités qu'il prévoit de
                                diffuser ainsi que le
                                coût par publicité. Le coût total des publicités est calculé automatiquement, ainsi que
                                les charges
                                publicitaires quotidiennes et hebdomadaires. Pour les coûts des produits, l'utilisateur
                                doit
                                indiquer le nombre de produits qu'il prévoit de vendre ainsi que le coût unitaire,
                                tandis que le
                                coût total des produits est calculé automatiquement.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 d-flex">
                        <div class="alert alert-primary w-100 d-flex flex-column">
                            <h1 class="fs-8">2</h1>
                            <p class="flex-grow-1">Dans la section du centre d'appels, l'utilisateur doit entrer le
                                nombre de leads
                                confirmés et livrés ainsi que leurs coûts respectifs, et le système calculera
                                automatiquement le coût
                                total des leads confirmés et livrés. Pour les frais de livraison et de retour,
                                l'utilisateur
                                doit saisir le nombre de livraisons et de retours ainsi que leurs coûts, tandis que le
                                total
                                des frais de livraison et de retour est déterminé automatiquement. L'utilisateur doit
                                également renseigner le nombre de leads générés ainsi que leur coût, le coût total des
                                leads
                                étant calculé automatiquement. Le montant de l’investissement est déterminé en
                                additionnant
                                automatiquement tous les coûts associés.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 d-flex">
                        <div class="alert alert-secondary w-100 d-flex flex-column">
                            <h1 class="fs-8">3</h1>
                            <p class="flex-grow-1">Dans la section du chiffre d'affaires, le nombre de leads ayant
                                abouti à une vente est
                                pré-rempli sur la base d’un autre champ, et l'utilisateur doit saisir le prix unitaire
                                du produit vendu.
                                Le chiffre d'affaires total est ensuite calculé automatiquement. Le résultat avant
                                charges est déterminé en
                                fonction des valeurs du chiffre d'affaires et de l’investissement. Enfin, la marge
                                bénéficiaire est calculée
                                automatiquement afin de donner à l'utilisateur une vision claire de sa rentabilité.</p>
                        </div>
                    </div>
                </div>






                <div class="row mt-4 h-100">
                    <div class="col-lg-8 d-flex flex-column">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center">
                                <span class="badge bg-info" style="margin-right: 10px;"><i
                                        class="ti ti-coin fs-6"></i></span>
                                <h4 class="card-title fs-6">Besoin en Investissement</h4>

                            </div>
                            <div class="mb-4 p-4 text-center d-flex flex-column justify-content-center h-100">
                                <form class="h-100 d-flex flex-column justify-content-center">
                                    <div class="row gx-4 mt-4">
                                        <div class="col-6">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">ADS</label>
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Qty"
                                                id="ads_qty">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Cost"
                                                id="ads_cost">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Total"
                                                disabled id="ads_total">
                                        </div>
                                    </div>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-6">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Produit</label>
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Qty"
                                                id="product_qty">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Cost"
                                                id="product_cost">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Total"
                                                disabled id="product_total">
                                        </div>
                                    </div>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-6">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Centre d'appel
                                                / Confirmés</label>
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Qty"
                                                id="call_center_confirmed_qty">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Cost"
                                                id="call_center_confirmed_cost">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Total"
                                                disabled id="call_center_confirmed_total">
                                        </div>
                                    </div>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-6">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Centre d'appel
                                                / Livrés</label>
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Qty"
                                                id="call_center_delivered_qty">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Cost"
                                                id="call_center_delivered_cost">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Total"
                                                disabled id="call_center_delivered_total">
                                        </div>
                                    </div>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-6">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Frais de
                                                livraison</label>
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Qty"
                                                id="delivery_fees_qty">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Cost"
                                                id="delivery_fees_cost">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Total"
                                                disabled id="delivery_fees_total">
                                        </div>
                                    </div>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-6">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Frais de
                                                retour</label>
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Qty"
                                                id="return_fees_qty">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Cost"
                                                id="return_fees_cost">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Total"
                                                disabled id="return_fees_total">
                                        </div>
                                    </div>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-6">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Lead</label>
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Qty"
                                                id="lead_qty">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Cost"
                                                id="lead_cost">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Total"
                                                disabled id="lead_total">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 d-flex flex-column">
                        <div class="card flex-grow-1">
                            <div class="card-body d-flex align-items-center">
                                <span class="badge bg-info" style="margin-right: 10px;"><i
                                        class="ti ti-truck fs-6"></i></span>
                                <h4 class="card-title fs-6">GOAL Commande à livré</h4>

                            </div>
                            <div class="mb-4 p-4 text-center">
                                <form>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-4">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">N°
                                                Lead</label>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control rounded" placeholder="Qty"
                                                id="nbr_lead_qty" disabled>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control rounded" value="-" disabled
                                                id="nbr_lead_rate">
                                        </div>
                                    </div>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-4">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">N°
                                                confirmé</label>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control rounded" placeholder="Qty"
                                                id="nbr_lead_confirmed_qty">
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control rounded" placeholder="%"
                                                id="nbr_lead_confirmed_rate">
                                        </div>
                                    </div>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-4">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">N° Lead
                                                Livré</label>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control rounded" placeholder="Qty"
                                                id="nbr_lead_delivered_qty">
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control rounded" placeholder="%"
                                                id="nbr_lead_delivered_rate">
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <div class="card flex-grow-1 mt-3">
                            <div class="card-body d-flex align-items-center">
                                <span class="badge bg-info" style="margin-right: 10px;"><i
                                        class="ti ti-presentation fs-6"></i></span>
                                <h4 class="card-title fs-6">RESULTAT before charge</h4>

                            </div>
                            <div class="mb-0 p-4 text-center">
                                <form>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">CHIFFRE
                                                D'AFFAIRE</label>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control rounded" placeholder=""
                                                id="turnover" disabled>
                                        </div>
                                    </div>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">INVESTISSEMENT</label>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control rounded" placeholder=""
                                                id="investement" disabled>
                                        </div>
                                    </div>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4 fw-semibold"
                                                style="display: flex; align-items: center; width: 100%;">RESULTAT before
                                                charge</label>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control rounded" placeholder=""
                                                id="result_before_charges" disabled>
                                        </div>
                                    </div>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-8">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Marge sur
                                                benefice</label>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control rounded" placeholder=""
                                                id="profit_margin" disabled>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <div class="card">
                            <div class="card-body d-flex align-items-center">
                                <span class="badge bg-info" style="margin-right: 10px;"><i
                                        class="ti ti-coins fs-6"></i></span>
                                <h4 class="card-title fs-6">Chiffre D'affaire</h4>

                            </div>
                            <div class="mb-4 p-4 text-center">
                                <form>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-4">
                                            <label for="" class="form-select-lg fs-4"
                                                style="display: flex; align-items: center; width: 100%;">Chiffre
                                                d'affaire</label>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" class="form-control rounded" placeholder="Qty"
                                                id="turnover_qty" disabled>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" class="form-control rounded"
                                                placeholder="Prix de vente" id="price">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control rounded" placeholder="Total"
                                                id="total" disabled>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <div class="card">
                            <div class="card-body d-flex align-items-center">
                                <span class="badge bg-info" style="margin-right: 10px;"><i
                                        class="ti ti-coins fs-6"></i></span>
                                <h4 class="card-title fs-6">Répartition des dépenses ads</h4>

                            </div>
                            <div class="mb-4 p-4 text-center">
                                <form>
                                    <div class="row gx-4 mt-4">
                                        <div class="col-6">
                                            <input type="number" class="form-control rounded" placeholder="quotidiennes"
                                                id="ads_charges_daily" disabled>
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control rounded"
                                                placeholder="hebdomadaires" id="ads_charges_weekly" disabled>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>









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
        document.addEventListener("DOMContentLoaded", function () {
            function calculateTotal(qtyId, costId, totalId) {
                const qtyInput = document.getElementById(qtyId);
                const costInput = document.getElementById(costId);
                const totalInput = document.getElementById(totalId);

                function updateTotal() {
                    const qty = parseFloat(qtyInput.value) || 0;
                    const cost = parseFloat(costInput.value) || 0;
                    totalInput.value = (qty * cost).toFixed(2);
                    updateInvestement(); // Call updateInvestement() after each total update
                }

                qtyInput.addEventListener("input", updateTotal);
                costInput.addEventListener("input", updateTotal);
            }

            const fields = [
                ["ads_qty", "ads_cost", "ads_total"],
                ["product_qty", "product_cost", "product_total"],
                ["call_center_confirmed_qty", "call_center_confirmed_cost", "call_center_confirmed_total"],
                ["call_center_delivered_qty", "call_center_delivered_cost", "call_center_delivered_total"],
                ["delivery_fees_qty", "delivery_fees_cost", "delivery_fees_total"],
                ["return_fees_qty", "return_fees_cost", "return_fees_total"],
                ["lead_qty", "lead_cost", "lead_total"]
            ];

            fields.forEach(ids => calculateTotal(...ids));

            function updateInvestement() {
                let totalInvestement = 0;
                const totalFields = [
                    "ads_total", "product_total", "call_center_confirmed_total",
                    "call_center_delivered_total", "delivery_fees_total",
                    "return_fees_total", "lead_total"
                ];

                totalFields.forEach(fieldId => {
                    const totalField = document.getElementById(fieldId);
                    totalInvestement += parseFloat(totalField.value) || 0;
                });

                const investementInput = document.getElementById("investement");
                investementInput.value = totalInvestement.toFixed(2);

                updateAdsCharges(); // Update ads_charges_daily and ads_charges_weekly
                updateResultBeforeCharges(); // Ensure result_before_charges is updated
            }

            function updateAdsCharges() {
                const adsTotal = parseFloat(document.getElementById("ads_total").value) || 0;
                const dailyInput = document.getElementById("ads_charges_daily");
                const weeklyInput = document.getElementById("ads_charges_weekly");

                dailyInput.value = (adsTotal / 20).toFixed(2);
                weeklyInput.value = (adsTotal / 4).toFixed(2);
            }

            function updateResultBeforeCharges() {
                const turnoverInput = document.getElementById("turnover");
                const investementInput = document.getElementById("investement");
                const resultBeforeChargesInput = document.getElementById("result_before_charges");

                const turnover = parseFloat(turnoverInput.value) || 0;
                const investement = parseFloat(investementInput.value) || 0;

                const resultBeforeCharges = turnover - investement;
                resultBeforeChargesInput.value = resultBeforeCharges.toFixed(2);

                updateProfitMargin(); // Ensure profit_margin is updated
            }

            function updateProfitMargin() {
                const resultBeforeCharges = parseFloat(document.getElementById("result_before_charges").value) || 0;
                const turnover = parseFloat(document.getElementById("turnover").value) || 0;
                const profitMarginInput = document.getElementById("profit_margin");

                if (turnover === 0) {
                    profitMarginInput.value = "0.00"; // Avoid division by zero
                } else {
                    profitMarginInput.value = ((resultBeforeCharges / turnover) * 100).toFixed(2);
                }
            }

            const turnoverInput = document.getElementById("turnover");
            const investementInput = document.getElementById("investement");

            turnoverInput.addEventListener("input", function () {
                updateResultBeforeCharges();
                updateInvestement();
            });

            investementInput.addEventListener("input", function () {
                updateResultBeforeCharges();
                updateInvestement();
            });

            function TotalAmountTurnover() {
                const turnoverQtyInput = document.getElementById("turnover_qty");
                const priceInput = document.getElementById("price");
                const totalInput = document.getElementById("total");
                const turnoverInput = document.getElementById("turnover");

                function calculateTotal() {
                    const qty = parseFloat(turnoverQtyInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;

                    const total = qty * price;
                    totalInput.value = total.toFixed(2);
                    turnoverInput.value = total.toFixed(2);
                }

                turnoverQtyInput.addEventListener("input", calculateTotal);
                priceInput.addEventListener("input", calculateTotal);
            }

            TotalAmountTurnover();

            function copyNbrLeadDeliveredQtyToTurnover() {
                const nbrLeadDeliveredQtyInput = document.getElementById("nbr_lead_delivered_qty");
                const turnoverQtyInput = document.getElementById("turnover_qty");

                nbrLeadDeliveredQtyInput.addEventListener("input", function () {
                    turnoverQtyInput.value = nbrLeadDeliveredQtyInput.value;
                });
            }

            copyNbrLeadDeliveredQtyToTurnover();

            function calculateNbrLeadQty() {
                const confirmedQtyInput = document.getElementById("nbr_lead_confirmed_qty");
                const confirmedRateInput = document.getElementById("nbr_lead_confirmed_rate");
                const deliveredQtyInput = document.getElementById("nbr_lead_delivered_qty");
                const deliveredRateInput = document.getElementById("nbr_lead_delivered_rate");
                const nbrLeadQtyInput = document.getElementById("nbr_lead_qty");

                function updateNbrLeadQty() {
                    const confirmedQty = parseFloat(confirmedQtyInput.value) || 0;
                    const confirmedRate = parseFloat(confirmedRateInput.value) || 0;
                    const deliveredQty = parseFloat(deliveredQtyInput.value) || 0;
                    const deliveredRate = parseFloat(deliveredRateInput.value) || 0;

                    const totalConfirmed = confirmedQty * (confirmedRate / 100);
                    const totalDelivered = deliveredQty * (deliveredRate / 100);

                    nbrLeadQtyInput.value = Math.round(totalConfirmed + totalDelivered);
                }

                confirmedQtyInput.addEventListener("input", updateNbrLeadQty);
                confirmedRateInput.addEventListener("input", updateNbrLeadQty);
                deliveredQtyInput.addEventListener("input", updateNbrLeadQty);
                deliveredRateInput.addEventListener("input", updateNbrLeadQty);
            }

            calculateNbrLeadQty();
        });





    </script>


</body>

</html>