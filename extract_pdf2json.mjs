import fs from 'fs';
import PDFParser from 'pdf2json';

const pdfParser = new PDFParser(this, 1);

pdfParser.on("pdfParser_dataError", errData => console.error(errData.parserError));
pdfParser.on("pdfParser_dataReady", pdfData => {
    fs.writeFileSync('pdf_content.txt', pdfParser.getRawTextContent());
    console.log("Success");
});

pdfParser.loadPDF("c:/Ragil/Patriot Metric/patriotmetric/dokumen/referensi/PEDOMAN-PATRIOT METRIC UPN Veteran Jatim (1) (1).pdf");
