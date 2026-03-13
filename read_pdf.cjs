const fs = require('fs');
const pdf = require('pdf-parse');

let dataBuffer = fs.readFileSync('c:/Ragil/Patriot Metric/patriotmetric/dokumen/referensi/PEDOMAN-PATRIOT METRIC UPN Veteran Jatim (1) (1).pdf');

pdf(dataBuffer).then(function(data) {
    console.log(data.text);
}).catch(function(error){
    console.error(error);
});
