<?php
require("db.php"); // Ensure you have the database connection
require(__DIR__."/../invoicr/invlib/invoicr.php");

$invoiceId = $_GET['invoiceId'];
$locale = $pdo->query("SELECT value FROM settings WHERE key = 'locale'")->fetchColumn();
if($locale === false) {
  $locale = "en_US";
}
$vat = $pdo->query("SELECT value FROM settings WHERE key = 'vat'")->fetchColumn() ?? 0;
$invoiceNumberFormat = $pdo->query("SELECT value FROM settings WHERE key = 'invoice_number_format'")->fetchColumn();
if($invoiceNumberFormat === false) {
  $invoiceNumberFormat = "Y-%03d";
}
$percFormatter = new NumberFormatter($locale, NumberFormatter::PERCENT);
$currencyFormatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

$companyInfo = $pdo->query("
  SELECT (
    SELECT value FROM settings WHERE key = 'company_name'
  ) as company_name,
  (
    SELECT value FROM settings WHERE key = 'company_logo'
  ) as company_logo,
  (
    SELECT value FROM settings WHERE key = 'company_phone'
  ) as company_phone,
  (
    SELECT value FROM settings WHERE key = 'company_email'
  ) as company_email,
  (
    SELECT value FROM settings WHERE key = 'company_website'
  ) as company_website,
  (
    SELECT value FROM settings WHERE key = 'company_address'
  ) as company_address,
  (
    SELECT value FROM settings WHERE key = 'payment_details'
  ) as payment_details;
")->fetch();
$invoice = $pdo->query("SELECT * FROM invoices WHERE id = $invoiceId")->fetch(PDO::FETCH_ASSOC);
$items = $pdo->query("SELECT i.*, price * quantity as total_price FROM invoice_items as i WHERE invoice_id = $invoiceId")->fetchAll(PDO::FETCH_ASSOC);
$client = $pdo->query("SELECT * FROM clients WHERE id = " . $invoice['client_id'])->fetch(PDO::FETCH_ASSOC);
$invoiceNumberFormat = str_replace("Y", date("Y", strtotime($invoice["issue_date"])), $invoiceNumberFormat);
$invoicr->set("company", [
  $companyInfo["company_logo"] ?? "/public/img/company_logo.png",
  "C:/Users/giova/repos/other/personal/invoicing/public/img/company_logo.png",
  $companyInfo["company_name"],
  $companyInfo["company_address"],
  "Phone: " . $companyInfo["company_phone"],
  $companyInfo["company_website"],
  "<a style=\"text-decoration: none; color: inherit\" href=\"mailto:" . $companyInfo["company_email"] . "\">" . $companyInfo["company_email"] . "</a>"
]);

// (B2) INVOICE HEADER
$invoicr->set("head", [
	["Invoice #", sprintf($invoiceNumberFormat, $invoice["invoice_number"])],
	["P.O. reference", $invoice["po_reference"]],
	["Due Date", $invoice["due_date"]]
]);

$invoicr->set("billto", [
  $client["name"],
  $client["federal_id"],
  str_replace("\\n", "<br />", $client["address"]),
]);

$invoiceItems = [];
foreach ($items as $item) {
  $invoiceItems[] = [
    $item["description"],
    $item["subdescription"],
    $item["quantity"],
    $currencyFormatter->formatCurrency($item["price"], $item["currency"]),
    $currencyFormatter->formatCurrency($item["quantity"] * $item["price"], $item["currency"]),
  ];
}
$totals = [];
$invoicr->set("items", $invoiceItems);
$subTotal = array_sum(array_column($items, "total_price"));
$afterDiscount = $subTotal - ($subTotal * $invoice['discount']);
$vatAmount = $afterDiscount * $vat;
$total = $afterDiscount + $vatAmount;

$totals[] = ["SUB-TOTAL", $currencyFormatter->formatCurrency($subTotal, $items[0]["currency"])];
if($invoice['discount'] > 0) {
  $totals[] = ["DISCOUNT " . $percFormatter->format($invoice["discount"]), "-" . $currencyFormatter->formatCurrency($subTotal * $invoice['discount'], $items[0]["currency"])];
}
$totals[] = ["VAT " . $percFormatter->format($vat), $currencyFormatter->formatCurrency($vatAmount, $items[0]["currency"])];
$totals[] = ["TOTAL", $currencyFormatter->formatCurrency($total, $items[0]["currency"])];
$invoicr->set("totals", $totals);
if ($companyInfo["payment_details"] !== NULL) {
  $invoicr->set("notes", [
    "<strong>Payment Details:</strong>",
    $companyInfo["payment_details"
  ]]);
}

$invoicr->template("blueberry");
$invoicr->outputPDF();
$invoicr->reset();
exit();
?>