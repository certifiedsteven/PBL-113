<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_kandidat'])) {
    $id = $_POST['id_kandidat'];

    $sql = "SELECT * FROM kandidat WHERE id_kandidat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Kandidat</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #666666;
                    margin: 0;
                    padding: 20px;
                }
                .container {
                    max-width: 600px;
                    margin: auto;
                    background: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    position: relative; /* Tambahkan baris ini */
                }
                
                h2 {
                    text-align: center;
                    color: #333;
                }
                label {
                    display: block;
                    margin: 10px 0 5px;
                }
                input[type="text"], input[type="file"] {
                    width: 100%;
                    padding: 10px;
                    margin: 5px 0 20px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                }
                img {
                    max-width: 100%;
                    height: auto;
                    margin-bottom: 20px;
                }
                .btn-simpan {
                    width: 100%;
                    padding: 10px;
                    background-color:rgb(21, 146, 50);
                    color: white;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 16px;
                    transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
                }

                .btn-simpan:hover {
                    background-color: rgb(2, 102, 7); /* Warna hijau lebih gelap saat hover */
                    transform: translateY(-2px); /* Efek angkat saat hover */
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Bayangan saat hover */
                }
                .top-right-button {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    font-size: 24px;
                    text-decoration: none;
                    color: #fff;
                    background-color: #dc3545; /* Warna merah */
                    border: none;
                    border-radius: 50%;
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: background-color 0.3s, transform 0.3s;
                }

                .top-right-button:hover {
                    background-color: #c82333; /* Warna merah lebih gelap saat hover */
                    transform: scale(1.1); /* Efek zoom saat hover */
                }

                .close-icon {
                    font-size: 24px; /* Ukuran ikon silang */
                    line-height: 1; /* Mengatur tinggi baris untuk pusat */
                }
            </style>
        </head>
        <body>
            
            <div class="container">

            <a href="kandidat.php" class="top-right-button" title="Tutup">
                <span class="close-icon">&times;</span>
            </a>


                <h2>Edit Data Kandidat</h2>
            
                <form id="editForm" method="POST" action="simpan_kandidat.php" enctype="multipart/form-data">
                      <input type="hidden" name="id_kandidat" value="<?php echo htmlspecialchars($row['id_kandidat']); ?>">



                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>

                    <label for="foto_kandidat">Foto Kandidat:</label>
                   <img src="../img/kandidat/<?php echo htmlspecialchars($row['foto_kandidat']); ?>" alt="Foto Kandidat" width="100" height="100"px>
                    <input type="file" id="foto_kandidat" name="foto_kandidat" accept="image/*">

                    <button type="button" class="btn-simpan" data-toggle="modal" data-target="#confirmModal">Simpan</button>
                </form>
            </div>

            <!-- Modal Konfirmasi -->
            <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Perubahan</h5>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menyimpan perubahan?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" id="confirmBtn">Ya, Ubah</button>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script>
                // Handle confirm button click in modal
                document.getElementById('confirmBtn').addEventListener('click', function () {
                    document.getElementById('editForm').submit(); // Submit form if "Ya, Ubah" is clicked
                });
            </script>
        </body>
        </html>
        <?php
    } else {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='kandidat.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>