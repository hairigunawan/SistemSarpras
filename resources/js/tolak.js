document.addEventListener('DOMContentLoaded', function () {
    const rejectForm = document.getElementById('rejectForm');

    if (rejectForm) {
        rejectForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Mencegah form submit biasa yang menyebabkan reload halaman

            const formData = new FormData(rejectForm);
            const actionUrl = rejectForm.action;

            // Menampilkan loading state opsional
            const submitButton = rejectForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = 'Mengirim...';

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Memberi tahu Laravel bahwa ini adalah request AJAX
                }
            })
            .then(response => {
                // Kembalikan button ke state semula
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;

                if (!response.ok) {
                    // Jika response tidak ok (misalnya 500 error), throw error untuk ditangani di .catch
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Tutup modal
                    closeModal();

                    // Tampilkan notifikasi sukses
                    alert(data.message); // Bisa diganti dengan toast notification yang lebih elegan

                    // Arahkan ulang ke halaman daftar peminjaman
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        // Fallback jika redirect tidak ada di response
                        window.location.href = '{{ route("admin.peminjaman.index") }}';
                    }
                } else {
                    // Jika ada error dari server (misalnya validasi gagal)
                    // Tampilkan pesan error
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error submitting reject form:', error);
                // Kembalikan button ke state semula
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                alert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.');
            });
        });
    }

    // Fungsi untuk menutup modal, bisa dipanggil dari tempat lain juga
    window.closeModal = function() {
        const rejectModal = document.getElementById('rejectModal');
        if (rejectModal) {
            rejectModal.classList.add('hidden');
            // Reset form saat modal ditutup
            const form = rejectModal.querySelector('form');
            if (form) {
                form.reset();
            }
        }
    };
});
