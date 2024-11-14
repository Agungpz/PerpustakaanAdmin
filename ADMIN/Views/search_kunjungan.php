<?php
require_once '../core/database.php';
require_once '../Models/Kunjungan.php';

$database = new Database();
$db = $database->getConnection();
$kunjungan = new Kunjungan($db);

if (isset($_POST['query'])) {
    $search = $_POST['query'];
    $query = "SELECT k.id_kunjungan, u.nama_lengkap, u.kelas, u.no_kartu, k.tanggal_kunjungan, k.keperluan 
              FROM kunjungan k 
              JOIN user u ON k.id_user = u.id_user 
              WHERE u.nama_lengkap LIKE :search 
                 OR u.kelas LIKE :search 
                 OR u.no_kartu LIKE :search 
                 OR k.keperluan LIKE :search";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':search', '%' . $search . '%');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        echo '<table class="min-w-full bg-white shadow-md rounded-lg border-collapse table-auto">';
        // echo '<thead>
        //         <tr class="text-left bg-gray-200 font-bold text-gray-600 uppercase text-sm">
        //             <th class="py-2 px-4">No</th>
        //             <th class="py-2 px-4">Nama Lengkap</th>
        //             <th class="py-2 px-4">Kelas</th>
        //             <th class="py-2 px-4">No Kartu</th>
        //             <th class="py-2 px-4">Tanggal Kunjungan</th>
        //             <th class="py-2 px-4">Keperluan</th>
        //             <th class="py-2 px-4">Aksi</th>
        //         </tr>
        //       </thead>';
        echo '<tbody>';
        foreach ($results as $i => $visit) {
            echo '<tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-2 px-4 text-left whitespace-nowrap">' . ($i + 1) . '</td>
                    <td class="py-2 px-4 text-left">' . $visit['nama_lengkap'] . '</td>
                    <td class="py-2 px-4 text-left">' . $visit['kelas'] . '</td>
                    <td class="py-2 px-4 text-left">' . $visit['no_kartu'] . '</td>
                    <td class="py-2 px-4 text-left">' . $visit['tanggal_kunjungan'] . '</td>
                    <td class="py-2 px-4 text-left">' . $visit['keperluan'] . '</td>
                    <td class="py-2 px-4 text-left">
                        <a href="edit.php?id=' . $visit['id_kunjungan'] . '" class="text-blue-600">Edit</a>
                        <button onclick="confirmDelete(' . $visit['id_kunjungan'] . ')" class="text-red-700 ml-2">Delete</button>
                    </td>
                  </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p class="text-center text-gray-500">No results found.</p>';
    }
}
?>
