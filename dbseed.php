<?php
require 'bootstrap.php';

$statement = <<<EOS
    CREATE TABLE IF NOT EXISTS contact (
      `id` int(11) NOT NULL,
      `image` varchar(100),
      `firstname` varchar(100) NOT NULL,
      `lastname` varchar(100) NOT NULL,
      `email` varchar(100) NOT NULL,
      `phoneNumber` int(10) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    INSERT INTO contact
        (id, image, firstname, lastname, email, phonenumber)
    VALUES
        (1, null, 'Luis', 'Williams', 'luis@gmail.com', 5647891234),
        (2, null, 'Maria', 'McDonald', 'maria@outlook.com', 9847891234),
        (3, null, 'Masha', 'Martinez', 'masha@gmail.com', 5645691234),
        (4, null, 'Jane', 'Smith', 'jane@yahoo.com', 5647894534),
        (5, null, 'John', 'Smith', 'john@gmail.com', 5647891264)
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}
