import fs from 'fs';
import pdfParse from 'pdf-parse/lib/pdf-parse.js';

const file = fs.readFileSync('c:/Ragil/Patriot Metric/patriotmetric/dokumen/referensi/PEDOMAN-PATRIOT METRIC UPN Veteran Jatim (1) (1).pdf');

pdfParse(file).then(data => {
    fs.writeFileSync('pdf_content.txt', data.text);
    console.log('Success');
}).catch(err => {
    console.error(err);
});
