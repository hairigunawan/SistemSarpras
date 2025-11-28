const deleteForms = document.querySelectorAll('.form-delete');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const currentForm = this;

            Swal.fire({
                    title: 'Apakah Kamu yakin?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',

                    // Hapus warna bawaan agar class CSS yang bekerja
                    buttonsStyling: false,

                    customClass: {
                        popup: 'rounded-xl shadow-lg border w-full max-w-sm border-gray-100', // Style kotaknya
                        title: 'text-lg font-semibold text-gray-800',
                        
                        htmlContainer: 'text-gray-600 text-sm text-gray-600',
                        confirmButton: 'bg-red-600 text-sm mr-2 hover:bg-red-700 text-white font-normal py-1.5 px-6 rounded-lg ml-2', // Style tombol Hapus
                        cancelButton: 'bg-blue-100 hover:bg-blue-300 text-gray-800 font-normal text-sm py-1.5 px-6 rounded-lg'   // Style tombol Batal
                    }
                }).then((result) => {
                if (result.isConfirmed) {
                    currentForm.submit();
                }
            });
        });
    });
