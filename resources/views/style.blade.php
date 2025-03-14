<style>
/* Styling untuk container tabel */
.table-container {
    width: 100%;
    max-width: 100%;
    padding: 10px; /* Jarak dalam lebih kecil */
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    margin-left: auto;  /* Supaya tetap di tengah */
    margin-right: auto; /* Supaya tetap di tengah */
    max-width: 120%; /* Kurangi lebar agar lebih dekat ke tepi */
}

/* Styling tabel mengikuti AdminLTE */
#kategoriTable {
    width: 100%;
    border-collapse: collapse;
}

#kategoriTable th {
    color: #333;
    text-align: center;
    padding: 12px;
}

#kategoriTable td {
    padding: 10px;
    border-bottom: 1px solid #dee2e6;
}

/* Efek hover untuk baris */
#kategoriTable tbody tr:hover {
    background-color: #f1f1f1;
}

/* Styling tombol hapus agar sesuai AdminLTE */
.btn-danger {
    background-color: #dc3545;
    border: none;
    transition: 0.3s;
}

.btn-danger:hover {
    background-color: #bd2130;
    transform: scale(1.05);
}

/* Responsif */
@media (max-width: 768px) {
    .table-container {
        padding: 10px;
    }
}
</style>
