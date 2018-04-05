<?php
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).


include_once '../../konfiguracija.php'; 
provjeraOvlasti();


$izraz=$veza->prepare("select a.*, b.naziv as smjer from grupa a 
 inner join smjer b on a.smjer=b.sifra where a.sifra=:sifra");
$izraz->execute($_GET);
$grupa=$izraz->fetch(PDO::FETCH_OBJ);


require_once('../../TCPDF-6.2.17/config/tcpdf_config.php');
require_once('../../TCPDF-6.2.17/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("P", "mm", "A4", true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($naslovAPP);
$pdf->SetTitle('Ispis grupe ' . $grupa->naziv);
$pdf->SetSubject('Ispis');
$pdf->SetKeywords('Edunova, PDF, grupa');

// set default header data
$pdf->SetHeaderData("logo.png", PDF_HEADER_LOGO_WIDTH, "Detalji grupe", $grupa->naziv, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/hrv.php')) {
	require_once(dirname(__FILE__).'/lang/hrv.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect

// Set some content to print
$html = "
<h1>$grupa->naziv</h1>
<h2>$grupa->smjer</h2>
<table>
<thead>
	<tr>
		<th>Polaznik</th>
		<th>Broj ugovora</th>
		<th>Email</th>
	</tr>
</thead>
<tbody>
";

						$izraz = $veza->prepare("
					
						select 
							a.sifra,
							b.ime, 
							b.prezime, 
							a.brojugovora,
							b.email
						from polaznik a inner join osoba b on a.osoba=b.sifra
						inner join clan c on c.polaznik=a.sifra
						where c.grupa=:grupa
						order by b.prezime, b.ime");
					$izraz->bindParam(":grupa", $_GET["sifra"]);
					$izraz->execute();
					$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
					foreach ($rezultati as $red){
				
						$html = $html .  "
						<tr>
							<td>$red->prezime  $red->ime</td>
							<td>$red->brojugovora</td>
							<td>$red->email</td>
						</tr>
						";
					}
					$html = $html . "
					</tbody>
				</table>";

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($grupa->naziv . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
