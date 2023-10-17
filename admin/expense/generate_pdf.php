<?php
require_once("../../config.php");
require_once("../../vendor/tcpdf/tcpdf.php");
require_once("../../vendor/fpdi2/src/autoload.php");

class CustomPDF extends \setasign\Fpdi\Tcpdf\Fpdi
{
    protected $tplId;

    function Header()
    {
        if ($this->tplId === null) {
            $this->setSourceFile('../../docs/refferal-form.pdf'); // Path to your template
            $this->tplId = $this->importPage(1);
        }
        $size = $this->useImportedPage($this->tplId, 0, 0, 210);

        $this->SetFont('helvetica', 'B', 20);
        $this->SetTextColor(0);
        $this->SetXY(10, 10);
       

        $this->SetFont('helvetica', '', 12);
        $this->SetXY(10, 30);
    }

    function Footer()
    {
       
    }
}

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $recordId = intval($_POST['id']);
    $sql = "SELECT * FROM `running_balance` WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recordId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $recordDetails = $result->fetch_assoc();

        $pdf = new CustomPDF();
        $pdf->AddPage();

        // Add your custom content
        $x = 120; // Starting X position
$y = 72; // Starting Y position

// Add the specific data from the record
$pdf->SetXY($x, $y);
$pdf->MultiCell(0, 10, date('m/d/Y'));

$x = 65;
$y += 6; 

$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(0);
$pdf->SetXY($x, $y);

$firstName = $recordDetails['fname'];
$middleName = $recordDetails['mname'];
$lastName = $recordDetails['lname'];

// Concatenate the name parts with spaces
$name = mb_strtoupper("{$lastName}, {$firstName} {$middleName}", 'UTF-8');

$pdf->Cell(40, 10, $name, 0, 0, 'L');


$x = 80;
$y += 9;
$pdf->SetXY($x, $y);
$pdf->MultiCell(0, 10, $recordDetails['age']);

$x += 40;
$pdf->SetXY($x, $y);
$pdf->MultiCell(0, 10, $recordDetails['sex']);

$x = 65;
$y += 5;
$pdf->SetFont('helvetica', '', 10); 
$pdf->SetTextColor(0);
$pdf->SetXY($x, $y);
$name = mb_strtoupper($recordDetails['address'], 'UTF-8'); 
$pdf->Cell(40, 10, $name, 0, 0, 'L'); 

$x = 65;
$y += 7; 

$pdf->SetFont('helvetica', 'B', 12); 
$pdf->SetTextColor(0);
$pdf->SetXY($x, $y);
$name = mb_strtoupper($recordDetails['referred_to'], 'UTF-8'); 
$pdf->Cell(40, 10, $name, 0, 0, 'L'); 

$x = 65;
$y += 7;
$pdf->SetFont('helvetica', '', 10); 
$pdf->SetTextColor(0);
$pdf->SetXY($x, $y);
$name = mb_strtoupper($recordDetails['doctors'], 'UTF-8'); 
$pdf->Cell(40, 10, $name, 0, 0, 'L'); 

$x = 35;
$y += 30;
$disposition = $recordDetails['disposition'];
$pdf->SetXY($x, $y);

// Split the disposition into parts using ","
$dispositionParts = explode(',', $disposition);

$pdf->SetFont('dejavusans', '', 10); // Adjust the font and size
$pdf->SetTextColor(0); // Set the text color

foreach ($dispositionParts as $part) {
    // Use a Unicode checkmark symbol and set the font
    $checkIcon = "✔ "; // Unicode checkmark symbol

    // Add a checkmark and the disposition part
    $pdf->MultiCell(0, 10, $checkIcon . ' ' . $part, 0, 'L', false);
    
    // Set the X and Y position for the next line
    $pdf->SetXY($x, $pdf->GetY());
}

// Continue with other content






$x = 35;
$y += 77;
$pdf->SetFont('dejavusans', '', 12);
$pdf->SetTextColor(0);
$pdf->SetXY($x, $y);

$amount = number_format($recordDetails['amount'], 2); // Format amount with two decimal places and comma as thousands separator
$amountWithSign = '₱' . $amount; // Add the peso sign

$pdf->Cell(40, 10, $amountWithSign, 0, 0, 'L');


$x += 75;

$pdf->SetXY($x, $y);
$amountInWords = $recordDetails['amount_in_words'];
$pdf->SetFont('helvetica', 'B', 12);  // Set the font and size
$pdf->Cell(80, 10, $amountInWords, 0, 1, 'L'); // Adjust width and alignment as needed


$x = 47;
$y += 44.5;

$pdf->SetXY($x, $y);
$controlNumber = $recordDetails['control_number'];
$pdf->SetTextColor(255, 0, 0);  // Set the font color to red
$pdf->SetFont('helvetica', '', 10);  // Set the font and size
$pdf->Cell(40, 10, $controlNumber, 0, 1, 'L'); // Adjust width and alignment as needed
$pdf->SetTextColor(0);  // Reset the font color to black for subsequent content



        // Add other custom content here

        $pdfContent = $pdf->Output('', 'S');

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'pdfContent' => base64_encode($pdfContent)]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid or missing ID']);
}
