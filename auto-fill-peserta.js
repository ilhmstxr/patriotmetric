(async () => {
    // 1. Fungsi pembantu untuk mengambil data Alpine.js secara robust & selektif
    const getAlpineData = () => {
        const elements = document.querySelectorAll('[x-data]');
        for (const el of elements) {
            let data = null;
            if (el.__x && el.__x.$data) {
                data = el.__x.$data;
            } else if (window.Alpine && typeof window.Alpine.$data === 'function') {
                data = window.Alpine.$data(el);
            } else if (el._x_dataStack) {
                data = el._x_dataStack[0];
            }
            
            // Mencari element x-data yang memiliki properti rubrik (categories atau allQuestions)
            if (data && (data.categories !== undefined || data.allQuestions !== undefined)) {
                return data;
            }
        }
        return null;
    };

    const data = getAlpineData();
    if (!data) {
        console.error("%c[Error] Gagal menemukan element Alpine.js yang mengelola form rubrik. Pastikan Anda berada di halaman /dashboard/rubrik.", "color: #ff3333; font-weight: bold;");
        return;
    }

    // Jika form sedang memuat dari API, tunggu sampai selesai loading
    if (data.loading) {
        console.log("%c[Info] Form rubrik sedang memuat data dari API. Menunggu selesai...", "color: #ffaa00; font-weight: bold;");
        while (data.loading) {
            await new Promise(resolve => setTimeout(resolve, 500));
        }
        console.log("%c[Info] Form selesai dimuat. Memulai pengisian...", "color: #1b5e20; font-weight: bold;");
    }

    const questions = data.allQuestions;
    if (!questions || questions.length === 0) {
        console.warn("%c[Warning] Tidak ada pertanyaan yang ditemukan di dalam form. Pastikan data rubrik telah berhasil dimuat dari server.", "color: #ffaa00; font-weight: bold;");
        return;
    }

    console.log(`%c[Start] Menemukan ${questions.length} pertanyaan. Mulai mengisi secara otomatis...`, "color: #1b5e20; font-weight: bold; font-size: 14px;");

    const driveUrl = "https://drive.google.com/drive/folders/1hoKCxerLRDa7coJq0YIIYcxUC0AhBmVG";

    for (let i = 0; i < questions.length; i++) {
        const q = questions[i];
        console.log(`%c[${i + 1}/${questions.length}] Memproses ${q.code} (ID: ${q.id}, Tipe: ${q.type})...`, "color: #0284c7; font-weight: bold;");

        // Set tautan bukti drive
        data.links[q.id] = driveUrl;

        // Set jawaban berdasarkan tipe input
        if (q.type === 'pilihan_ganda') {
            if (q.options && q.options.length > 0) {
                // Pilih opsi pertama sebagai default
                data.answers[q.id] = q.options[0].id;
                console.log(`   -> Memilih opsi pertama: "${q.options[0].text}"`);
            } else {
                console.warn(`   -> Opsi tidak ditemukan untuk pilihan ganda ${q.code}`);
            }
        } else if (q.type === 'isian_singkat') {
            if (q.code === 'B.13') {
                // Format JSON khusus untuk B.13
                const b13Payload = {
                    lokal: { label: "Skala lokal / kota kabupaten / internal institusi", nilai: 2 },
                    regional: { label: "Skala regional / provinsi", nilai: 1 },
                    nasional: { label: "Skala nasional", nilai: 1 },
                    internasional: { label: "Skala internasional", nilai: 1 },
                    total_poin: 11
                };
                data.answers[q.id] = JSON.stringify(b13Payload);
                console.log(`   -> Mengisi isian skala B.13 dengan default JSON.`);
            } else {
                // Isi dengan angka 5 sebagai nilai dummy/default
                data.answers[q.id] = 5;
                console.log(`   -> Mengisi nilai angka: 5`);
            }
        } else if (q.type === 'otomatis_sistem') {
            // Evaluasi otomatis sistem (seperti C.6)
            if (typeof data.computeAutomatic === 'function') {
                data.answers[q.id] = data.computeAutomatic(q) || "3";
            } else {
                data.answers[q.id] = "3";
            }
            console.log(`   -> Mengisi otomatis sistem: ${data.answers[q.id]}`);
        }

        // Kirim request penyimpanan ke API secara sequential (berurutan)
        if (typeof data.autoSave === 'function') {
            try {
                await data.autoSave(q.id);
                console.log(`   -> %cBerhasil tersimpan ke server.`, "color: #16a34a;");
            } catch (err) {
                console.error(`   -> %cGagal melakukan auto-save untuk ${q.code}:`, "color: #dc2626;", err);
            }
        } else if (typeof data.scheduleAutoSave === 'function') {
            data.scheduleAutoSave(q.id);
            console.log(`   -> Auto-save dijadwalkan.`);
        }

        // Delay 200ms per-pertanyaan agar request tidak bentrok (rate limiting / overload)
        await new Promise(resolve => setTimeout(resolve, 200));
    }

    console.log("%c[Success] Pengisian otomatis selesai! Seluruh isian telah tersimpan di server.", "color: #1b5e20; font-weight: bold; font-size: 14px;");
})();
