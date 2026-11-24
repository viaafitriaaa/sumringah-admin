<?php

// Reference data for the local UI demo. No prices or payment rules here are production rules.
$categories = [
    'paket-hemat' => ['name' => 'Paket Hemat', 'image' => 'cat-paket.png'],
    'pernasian-duniah' => ['name' => 'Pernasian Duniah', 'image' => 'cat-nasi.png'],
    'perkopian-duniawi' => ['name' => 'Perkopian Duniawi', 'image' => 'cat-kopi.png'],
    'jajanan-manis' => ['name' => 'Jajanan Manis Kaya Kamu', 'image' => 'cat-jajan.png'],
];

$products = [
    ['paket-1', 'paket-hemat', 'Paket Hemat 1', 17000, 'Kwetiau goreng spesial dengan es teh.', 'kwetiau.png'],
    ['paket-2', 'paket-hemat', 'Paket Hemat 2', 17000, 'Kwetiau goreng ayam dan es teh.', 'kwetiau.png'],
    ['paket-3', 'paket-hemat', 'Paket Hemat 3', 17000, 'Kwetiau goreng telur dan teh hangat.', 'kwetiau.png'],
    ['paket-4', 'paket-hemat', 'Paket Hemat 4', 17000, 'Kwetiau goreng bakso dan es teh.', 'kwetiau.png'],
    ['paket-5', 'paket-hemat', 'Paket Hemat 5', 17000, 'Kwetiau sambal dengan es teh.', 'kwetiau.png'],
    ['paket-6', 'paket-hemat', 'Paket Hemat 6', 17000, 'Kwetiau goreng sayur dengan es teh.', 'kwetiau.png'],
    ['paket-7', 'paket-hemat', 'Paket Hemat 7', 17000, 'Kwetiau goreng pedas dan es teh.', 'kwetiau.png'],
    ['kwetiau-spesial', 'pernasian-duniah', 'Kwetiau Goreng Spesial', 17000, '1 porsi kwetiau goreng spesial dengan isi udang dan bakso.', 'kwetiau.png'],
    ['nasi-goreng', 'pernasian-duniah', 'Nasi Goreng Kampung', 18000, 'Nasi goreng dengan telur dan acar.', 'cat-nasi.png'],
    ['nasi-ayam', 'pernasian-duniah', 'Nasi Ayam Suwir', 19000, 'Nasi hangat, ayam suwir pedas, dan lalapan.', 'cat-nasi.png'],
    ['kwetiau-bakso', 'pernasian-duniah', 'Kwetiau Goreng Bakso', 16000, 'Kwetiau gurih dengan irisan bakso.', 'kwetiau.png'],
    ['kwetiau-pedas', 'pernasian-duniah', 'Kwetiau Goreng Pedas', 17000, 'Kwetiau goreng dengan sambal racikan warkop.', 'kwetiau.png'],
    ['nasi-telur', 'pernasian-duniah', 'Nasi Telur Sambal', 14000, 'Telur ceplok dan sambal rumahan.', 'cat-nasi.png'],
    ['nasi-goreng-telur', 'pernasian-duniah', 'Nasi Goreng Telur', 16000, 'Nasi goreng kecap dengan telur.', 'cat-nasi.png'],
    ['kopi-susu', 'perkopian-duniawi', 'Es Kopi Susu', 15000, 'Kopi susu dingin dengan gula aren.', 'cat-kopi.png'],
    ['kopi-tubruk', 'perkopian-duniawi', 'Kopi Tubruk', 10000, 'Kopi hitam diseduh langsung.', 'cat-kopi.png'],
    ['es-latte', 'perkopian-duniawi', 'Es Latte', 17000, 'Espresso dan susu segar.', 'cat-kopi.png'],
    ['pisang-bakar', 'jajanan-manis', 'Pisang Bakar Cokelat', 13000, 'Pisang bakar dengan taburan cokelat.', 'cat-jajan.png'],
    ['roti-bakar', 'jajanan-manis', 'Roti Bakar Keju', 14000, 'Roti bakar hangat dengan keju.', 'cat-jajan.png'],
    ['donat', 'jajanan-manis', 'Donat Gula', 9000, 'Donat lembut dengan taburan gula.', 'cat-jajan.png'],
];

$items = [];
foreach ($products as [$id, $category, $name, $price, $description, $photo]) {
    $items[$id] = [
        'id' => $id,
        'category' => $category,
        'nama_menu' => $name,
        'kategori' => $categories[$category]['name'],
        'deskripsi' => $description,
        'harga' => $price,
        'foto' => $photo,
        'stok' => $id === 'donat' ? 0 : 20,
        'status' => $id === 'donat' ? 'habis' : 'tersedia',
        'hasOptions' => in_array($category, ['paket-hemat', 'pernasian-duniah'], true),
    ];
}

return [
    'table' => 'ACL2-05',
    'timeZone' => 'Asia/Jakarta',
    'taxRate' => 10,
    'categories' => $categories,
    'items' => $items,
    'highlights' => [
        ['title' => 'Spesial Menu', 'category' => 'pernasian-duniah', 'items' => ['kwetiau-spesial', 'kwetiau-bakso', 'kwetiau-pedas']],
        ['title' => 'PROMO', 'category' => 'paket-hemat', 'items' => ['paket-1', 'paket-4', 'paket-7']],
        ['title' => 'Menu Baru', 'category' => 'pernasian-duniah', 'items' => ['kwetiau-pedas', 'nasi-ayam', 'kopi-susu']],
    ],
    'options' => [
        'lunch-box' => ['name' => 'Lunch Box', 'price' => 1000, 'max' => 1],
        'bakso' => ['name' => 'Add On Bakso', 'price' => 2000, 'max' => 5],
        'sosis' => ['name' => 'Add On Sosis', 'price' => 2000, 'max' => 5],
        'telur-dadar' => ['name' => 'Add On Telur Dadar', 'price' => 3000, 'max' => 0],
        'telur-ceplok' => ['name' => 'Add On Ceplok', 'price' => 2000, 'max' => 5],
        'kerupuk' => ['name' => 'Add On Kerupuk Udang', 'price' => 2000, 'max' => 5],
        'mendoan' => ['name' => 'Add On Mendoan 1 pcs', 'price' => 2000, 'max' => 5],
    ],
    'outlet' => [
        'name' => 'Warkop Sumringah',
        'address' => 'Jl. Profesor DR. HR Boenyamin No.677, Dukuhbandong, Grendeng, Kec. Purwokerto Utara, Kabupaten Banyumas, Jawa Tengah 53121',
        'hours' => [
            'Senin' => '08.00–02.00 WIB',
            'Selasa' => '08.00–02.00 WIB',
            'Rabu' => '08.00–02.00 WIB',
            'Kamis' => '08.00–02.00 WIB',
            'Jumat' => '08.00–02.00 WIB',
            'Sabtu' => '24 jam',
            'Minggu' => '24 jam',
        ],
    ],
];
