    document.addEventListener('DOMContentLoaded', function () {
        const proyektorSelect = document.getElementById('id_proyektor');
        const ruanganSelect = document.getElementById('id_ruangan');
        const lokasiProyektorContainer = document.getElementById('lokasi_proyektor_container');
        const ruanganProyektorContainer = document.getElementById('ruangan_proyektor_container');
        const form = document.getElementById('peminjamanForm');
        const selectedSarprasInfo = document.getElementById('selected-sarpras-info');
        const sarprasList = document.getElementById('sarpras-list');

        // Menyimpan state peminjaman yang sudah diajukan
        let submittedPins = [];

        function toggleProyektorFields() {
            if (proyektorSelect.value) {
                lokasiProyektorContainer.style.display = 'block';
                ruanganProyektorContainer.style.display = 'block';
            } else {
                lokasiProyektorContainer.style.display = 'none';
                ruanganProyektorContainer.style.display = 'none';
            }
        }

        function updateSelectedSarprasInfo() {
            const selectedRuangan = ruanganSelect.options[ruanganSelect.selectedIndex].text;
            const selectedProyektor = proyektorSelect.options[proyektorSelect.selectedIndex].text;

            let sarprasText = '';

            if (ruanganSelect.value && proyektorSelect.value) {
                sarprasText = `${selectedRuangan} dan ${selectedProyektor}`;
            } else if (ruanganSelect.value) {
                sarprasText = selectedRuangan;
            } else if (proyektorSelect.value) {
                sarprasText = selectedProyektor;
            }

            if (sarprasText) {
                sarprasList.textContent = sarprasText;
                selectedSarprasInfo.classList.remove('hidden');
            } else {
                selectedSarprasInfo.classList.add('hidden');
            }
        }

        function checkDuplicatePeminjaman() {
            const tanggalPinjam = document.getElementById('tanggal_pinjam').value;
            const jamMulai = document.getElementById('jam_mulai').value;
            const jamSelesai = document.getElementById('jam_selesai').value;
            const selectedRuangan = ruanganSelect.value;
            const selectedProyektor = proyektorSelect.value;

            // Cek apakah sudah ada peminjaman dengan kombinasi yang sama
            const isDuplicate = submittedPins.some(pin =>
                pin.tanggalPinjam === tanggalPinjam &&
                pin.jamMulai === jamMulai &&
                pin.jamSelesai === jamSelesai &&
                ((pin.selectedRuangan && selectedRuangan) || (pin.selectedProyektor && selectedProyektor))
            );

            if (isDuplicate) {
                alert('Anda sudah mengajukan peminjaman untuk waktu yang sama. Silakan hapus peminjaman sebelumnya atau pilih waktu yang berbeda.');
                return true;
            }

            return false;
        }

        function addSubmittedPin() {
            const tanggalPinjam = document.getElementById('tanggal_pinjam').value;
            const jamMulai = document.getElementById('jam_mulai').value;
            const jamSelesai = document.getElementById('jam_selesai').value;
            const selectedRuangan = ruanganSelect.value;
            const selectedProyektor = proyektorSelect.value;

            if (tanggalPinjam && jamMulai && jamSelesai && (selectedRuangan || selectedProyektor)) {
                submittedPins.push({
                    tanggalPinjam,
                    jamMulai,
                    jamSelesai,
                    selectedRuangan,
                    selectedProyektor
                });
            }
        }

        proyektorSelect.addEventListener('change', function() {
            toggleProyektorFields();
            updateSelectedSarprasInfo();
        });

        ruanganSelect.addEventListener('change', updateSelectedSarprasInfo);

        // Tambahkan event listener untuk validasi sebelum submit
        form.addEventListener('submit', function(e) {
            // Cek apakah minimal satu sarpras dipilih
            if (!ruanganSelect.value && !proyektorSelect.value) {
                e.preventDefault();
                alert('Pilih minimal satu Ruangan atau Proyektor.');
                return;
            }

            if (checkDuplicatePeminjaman()) {
                e.preventDefault();
                return;
            }

            // Tambahkan peminjaman ke daftar yang sudah diajukan
            addSubmittedPin();
        });

        // Initial check on page load
        toggleProyektorFields();
        updateSelectedSarprasInfo();
    });
