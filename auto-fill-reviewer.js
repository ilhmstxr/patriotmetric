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
            
            // Mencari element x-data yang memiliki properti rubrikData & reviewerScores
            if (data && data.rubrikData !== undefined && data.reviewerScores !== undefined) {
                return data;
            }
        }
        return null;
    };

    const data = getAlpineData();
    if (!data) {
        console.error("%c[Error] Gagal menemukan element Alpine.js yang mengelola penilaian reviewer. Pastikan Anda berada di halaman /reviewer/peserta/{id} dan halaman telah selesai dimuat.", "color: #ff3333; font-weight: bold;");
        return;
    }

    // Jika sedang loading, tunggu sampai selesai
    if (data.loading) {
        console.log("%c[Info] Halaman sedang memuat data dari API. Menunggu selesai...", "color: #ffaa00; font-weight: bold;");
        while (data.loading) {
            await new Promise(resolve => setTimeout(resolve, 500));
        }
        console.log("%c[Info] Halaman selesai dimuat. Memulai pengisian...", "color: #1b5e20; font-weight: bold;");
    }

    const questions = data.allQuestions;
    if (!questions || questions.length === 0) {
        console.warn("%c[Warning] Tidak ada pertanyaan/indikator yang ditemukan di dalam form.", "color: #ffaa00; font-weight: bold;");
        return;
    }

    console.log(`%c[Start] Menemukan ${questions.length} indikator rubrik. Mulai mengisi skor reviewer secara otomatis...`, "color: #1b5e20; font-weight: bold; font-size: 14px;");

    for (let i = 0; i < questions.length; i++) {
        const q = questions[i];
        
        // 1. Generate skor acak antara 1 dan 5
        const randomScore = Math.floor(Math.random() * 5) + 1;
        data.reviewerScores[q.id] = randomScore;

        // 2. Isi catatan dummy (> 20 karakter untuk menghindari warning UI)
        const dummyNote = `Catatan penilaian untuk indikator ${q.kode_pertanyaan} sudah diverifikasi oleh tim reviewer.`;
        data.reviewerNotes[q.id] = dummyNote;

        console.log(`%c[${i + 1}/${questions.length}] Indikator ${q.kode_pertanyaan} (ID: ${q.id}) -> Skor: ${randomScore}, Catatan: "${dummyNote}"`, "color: #0284c7;");

        // 3. Kirim request penyimpanan ke API secara sequential (berurutan)
        if (typeof data.saveQuestionScore === 'function') {
            try {
                await data.saveQuestionScore(q.id);
                console.log(`   -> %cBerhasil tersimpan ke server.`, "color: #16a34a;");
            } catch (err) {
                console.error(`   -> %cGagal menyimpan skor untuk ${q.kode_pertanyaan}:`, "color: #dc2626;", err);
            }
        }

        // Delay 200ms agar request teratur
        await new Promise(resolve => setTimeout(resolve, 200));
    }

    console.log("%c[Success] Pengisian otomatis skor selesai! Silakan periksa kembali halaman penilaian.", "color: #1b5e20; font-weight: bold; font-size: 14px;");
})();
