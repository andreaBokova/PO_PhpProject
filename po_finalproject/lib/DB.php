<?php

namespace DASHBOARD\lib;

class DB
{
    private $host = "localhost";
    private $port = 3306;
    private $username = "root";
    private $password = "";
    private $dbName = "eshop_db";

    private \PDO $connection;

    public function __construct(
        string $host = "",
        int $port = 3306,
        string $username = "",
        string $password = "",
        string $dbName = ""
    ) {
        if (!empty($host)) {
            $this->host = $host;
        }

        if (!empty($port)) {
            $this->port = $port;
        }

        if (!empty($username)) {
            $this->username = $username;
        }

        if (!empty($password)) {
            $this->password = $password;
        }

        if (!empty($dbName)) {
            $this->dbName = $dbName;
        }

        try {
            $this->connection = new \PDO(
                "mysql:host=$this->host;dbname=$this->dbName;charset=utf8mb4",
                $this->username,
                $this->password
            );
            // set the PDO error mode to exception
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();


            throw $e;
        }
    }


    public function checkCredentials($username, $password)
    {

       
        $query = "SELECT id, password FROM account WHERE login = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the result as an associative array
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            // Access the values
            $storedPassword = $user['password'];

            // Verify the password
            if (password_verify($password, $storedPassword)) {
                // Password is correct, user is authenticated
                echo "<p style='color: green'>Authentication successful</p><br>";
                return $user['id'];
            } else {
                // Incorrect password
                echo "<p style='color: red'>Incorrect password</p><br>";
                return false;
            }
        } else {
            // Handle the case where no user was found
            echo "<p style='color: red'>User not found</p><br>";
            return false;
        }
    }



    public function getProductById($productId): array
    {
        $sql = "
            SELECT 
                p.id,
                p.account_id,
                p.name,
                p.brand,
                p.gender,
                p.type,
                p.material, 
                p.weight_in_grams,
                p.description,
                p.selling_price,
                p.units_in_stock,
                 p.image_url
            FROM 
                product p
            JOIN 
                account a ON a.id = p.account_id
            WHERE p.id = :product_id;
        ";

        $query = $this->connection->prepare($sql);
        $query->bindParam(':product_id', $productId, \PDO::PARAM_INT);
        $query->execute();

        $product_info = $query->fetch(\PDO::FETCH_ASSOC);

        if ($product_info) {
            return [
                'id' => $product_info['id'],
                'account_id' => $product_info['account_id'],
                'name' => $product_info['name'],
                'brand' => $product_info['brand'],
                'gender' => $product_info['gender'],
                'type' => $product_info['type'],
                'material' => $product_info['material'],
                'weight_in_grams' => $product_info['weight_in_grams'],
                'description' => $product_info['description'],
                'selling_price' => $product_info['selling_price'],
                'units_in_stock' => $product_info['units_in_stock'],
                'image_url' => $product_info['image_url'],

            ];
        }

        return []; // Return an empty array if no data is found
    }

    public function addSale($date, $accountId, $products)
    {
        try {
            // Start a transaction
            $this->connection->beginTransaction();

            
            $sql = "INSERT INTO sale (date, account_id) VALUES (:date, :account_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':account_id', $accountId);
            $stmt->execute();

            // Get the last inserted sale ID
            $saleId = $this->connection->lastInsertId();

            // Check and decrement units in stock for each product
            foreach ($products as $product) {
                $productName = $product['name'];
                $quantity = $product['quantity'];

                // Decrement units in stock and check if it's >= 0
                $result = $this->decrementUnitsInStock($productName, $quantity);

                if ($result >= 0) {
                    // Proceed with the sale_has_product insertion
                    $productId = $this->getProductIdByName($productName);

                    $sql = "INSERT INTO sale_has_product (sale_id, product_id, quantity) VALUES (:sale_id, :product_id, :quantity)";
                    $stmt = $this->connection->prepare($sql);
                    $stmt->bindParam(':sale_id', $saleId);
                    $stmt->bindParam(':product_id', $productId);
                    $stmt->bindParam(':quantity', $quantity);
                    $stmt->execute();
                } else {
                    // Rollback the transaction if units in stock would go below 0
                    $this->connection->rollBack();
                    return false;
                }
            }

            // Commit the transaction
            $this->connection->commit();
            return true;
        } catch (\PDOException $e) {
            // Rollback the transaction in case of an error
            $this->connection->rollBack();
            return false;
        }
    }



    private function getProductIdByName($productName)
    {
        $sql = "SELECT id FROM product WHERE name = :name";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':name', $productName);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return ($result) ? $result['id'] : null;
    }



    public function addProduct(
        string $name,
        string $brand,
        string $gender,
        string $type,
        string $material,
        float $weight_in_grams,
        string $description,
        float $selling_price,
        int $units_in_stock,
        string $image_url,
        string $account_id
    ): bool {
        $sql = "INSERT INTO product(name, brand, gender, type, material, weight_in_grams, description,
        selling_price, units_in_stock, image_url, account_id)
         VALUES (:name, :brand, :gender, :type, :material, :weight_in_grams, :description,
        :selling_price, :units_in_stock, :image_url, :account_id)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':material', $material);
        $stmt->bindParam(':weight_in_grams', $weight_in_grams);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':selling_price', $selling_price);
        $stmt->bindParam(':units_in_stock', $units_in_stock);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':account_id', $account_id);

        return $stmt->execute();
    }



    public function addUser($username, $password)
    {
        try {
            // Check if the username already exists
            $stmt = $this->connection->prepare("SELECT * FROM account WHERE login = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Username already exists
                echo "Username already exists";
                return false;
            }

            // Insert the new user
            $stmt = $this->connection->prepare("INSERT INTO account (login, password) VALUES (:username, :password)");
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();
            echo "User added successfully";
            return true; // User added successfully
        } catch (\PDOException $e) {
            // Handle the exception, log it, or display an error message
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    public function decrementUnitsInStock($productName, $quantity)
    {
        try {
            $sql = "UPDATE product SET units_in_stock = units_in_stock - :quantity WHERE name = :productName";
            $stmt = $this->connection->prepare($sql);

            $stmt->bindParam(':quantity', $quantity, \PDO::PARAM_INT);
            $stmt->bindParam(':productName', $productName, \PDO::PARAM_STR);

            $stmt->execute();

            // Check if the update was successful
            $rowCount = $stmt->rowCount();
            if ($rowCount > 0) {
                // Return the updated quantity
                return $this->getUnitsInStock($productName);
            } else {
                // Return false if the update didn't affect any rows
                return false;
            }
        } catch (\PDOException $e) {
            // You might want to return false or throw an exception depending on your needs
            echo "Error updating units_in_stock: " . $e->getMessage();
            return false;
        }
    }

    private function getUnitsInStock($productName)
    {
        try {
            $sql = "SELECT units_in_stock FROM product WHERE name = :productName";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':productName', $productName, \PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return ($result) ? $result['units_in_stock'] : false;
        } catch (\PDOException $e) {
           
            echo "Error retrieving units_in_stock: " . $e->getMessage();
            return false;
        }
    }


    private function incrementUnitsInStock($productId, $quantity)
    {
        $sql = "UPDATE product SET units_in_stock = units_in_stock + :quantity WHERE id = :product_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':quantity', $quantity, \PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, \PDO::PARAM_INT);
        $stmt->execute();
    }


    public function getLimitProducts($page, $itemsPerPage, $userId)
    {
        $offset = ($page - 1) * $itemsPerPage;

        // Prepare the SQL statement with a placeholder for :userId
        $sql = "SELECT * FROM product WHERE account_id = :userId LIMIT :offset, :itemsPerPage";
        $stmt = $this->connection->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindParam(':itemsPerPage', $itemsPerPage, \PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        $limitProducts = [];

        // Fetch the result as an associative array
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Extracting unique brands from the result set
        foreach ($data as $product_info) {
            $limitProducts[] = [
                'id' => $product_info['id'],
                'account_id' => $product_info['account_id'],
                'name' => $product_info['name'],
                'brand' => $product_info['brand'],
                'gender' => $product_info['gender'],
                'type' => $product_info['type'],
                'material' => $product_info['material'],
                'weight_in_grams' => $product_info['weight_in_grams'],
                'description' => $product_info['description'],
                'selling_price' => $product_info['selling_price'],
                'units_in_stock' => $product_info['units_in_stock'],
                'image_url' => $product_info['image_url'],
            ];
        }

        return $limitProducts;
    }

    public function getProducts($userId)
    {
        // Prepare the SQL statement with a placeholder for :userId
        $sql = "SELECT * FROM product WHERE account_id = :userId";
        $stmt = $this->connection->prepare($sql);

        // Bind the parameter
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        $products = [];

        // Fetch the result as an associative array
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Extracting product details from the result set
        foreach ($data as $productInfo) {
            $products[] = [
                'id' => $productInfo['id'],
                'account_id' => $productInfo['account_id'],
                'name' => $productInfo['name'],
                'selling_price' => $productInfo['selling_price'],
                'units_in_stock' => $productInfo['units_in_stock'],
            ];
        }

        return $products;
    }



    public function getAccountById($accountId): array
    {
        $sql = "
            SELECT 
                *
            FROM 
                account
            WHERE account.id = :account_id;
        ";

        $query = $this->connection->prepare($sql);
        $query->bindParam(':account_id', $accountId, \PDO::PARAM_INT);
        $query->execute();

        $account_info = $query->fetch(\PDO::FETCH_ASSOC);

        if ($account_info) {
            return [
                'id' => $account_info['id'],
                'login' => $account_info['login'],
                'password' => $account_info['password'],
                'profile_pic_url' => $account_info['profile_pic_url'],
            ];
        }

        return []; // Return an empty array if no data is found
    }



    public function deleteProduct(int $id): bool
    {
        $this->connection->beginTransaction();

        try {
            // Fetch sale_has_product entries related to the product
            $sqlGetSaleProductEntries = "SELECT sale_id FROM sale_has_product WHERE product_id = :product_id";
            $stmtGetSaleProductEntries = $this->connection->prepare($sqlGetSaleProductEntries);
            $stmtGetSaleProductEntries->bindParam(':product_id', $id, \PDO::PARAM_INT);
            $stmtGetSaleProductEntries->execute();

            $saleProductEntries = $stmtGetSaleProductEntries->fetchAll(\PDO::FETCH_ASSOC);

            // Delete entries from sale_has_product table based on product_id
            $sqlDeleteSaleProducts = "DELETE FROM sale_has_product WHERE product_id = :product_id";
            $stmtDeleteSaleProducts = $this->connection->prepare($sqlDeleteSaleProducts);
            $stmtDeleteSaleProducts->bindParam(':product_id', $id, \PDO::PARAM_INT);
            $stmtDeleteSaleProducts->execute();

            // Delete product entry
            $sqlDeleteProduct = "DELETE FROM product WHERE id = :product_id";
            $stmtDeleteProduct = $this->connection->prepare($sqlDeleteProduct);
            $stmtDeleteProduct->bindParam(':product_id', $id, \PDO::PARAM_INT);
            $stmtDeleteProduct->execute();

            $this->connection->commit();

            // If there are associated sale entries, delete them
            foreach ($saleProductEntries as $entry) {
                $this->deleteSale($entry['sale_id']);
            }

            return true; // Deletion successful
        } catch (\PDOException $e) {
            $this->connection->rollBack();
            return false; // Deletion failed
        }
    }


    public function getBrands($user_id): array
    {
        $sql = "
        SELECT DISTINCT
            p.brand
        FROM product p
        JOIN account a ON a.id = p.account_id
        WHERE p.account_id = :user_id;
    ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $brands = [];

        // Extracting unique brands from the result set
        foreach ($data as $brand_data) {
            $brands[] = $brand_data['brand'];
        }

        return $brands;
    }


    public function getSales($user_id): array
    {
        $sql = "
            SELECT
                s.id,
                s.date,
                s.account_id,
                p.name as product_name,
                p.selling_price,
                shp.quantity
            FROM sale s
            JOIN account a ON a.id = s.account_id
            LEFT JOIN sale_has_product shp ON shp.sale_id = s.id
            LEFT JOIN product p ON p.id = shp.product_id
            WHERE s.account_id = :user_id;
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $sales = [];

        // Organizing sales data
        foreach ($data as $sale_data) {
            $saleId = $sale_data['id'];

            // Check if the sale already exists in the result array
            if (!isset($sales[$saleId])) {
                $sales[$saleId] = [
                    'id' => $sale_data['id'],
                    'date' => $sale_data['date'],
                    'account_id' => $sale_data['account_id'],
                    'products' => [],
                ];
            }

            // Add product details to the sale
            $productDetails = [
                'product_name' => $sale_data['product_name'],
                'selling_price' => $sale_data['selling_price'],
                'quantity' => $sale_data['quantity'],

            ];

            $sales[$saleId]['products'][] = $productDetails;
        }

        return array_values($sales);
    }

    private function getProductInfoForSale($saleId)
    {
    
        $sql = "SELECT product_id, quantity FROM sale_has_product WHERE sale_id = :sale_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':sale_id', $saleId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }



    public function deleteSale($saleId): bool
    {
        $this->connection->beginTransaction();

        try {
            // Fetch product information before deleting the sale
            $productIdQuantity = $this->getProductInfoForSale($saleId);

            // Delete entries from sale_has_product table based on sale_id
            $sqlDeleteSaleProducts = "DELETE FROM sale_has_product WHERE sale_id = :sale_id";
            $stmtDeleteSaleProducts = $this->connection->prepare($sqlDeleteSaleProducts);
            $stmtDeleteSaleProducts->bindParam(':sale_id', $saleId, \PDO::PARAM_INT);
            $stmtDeleteSaleProducts->execute();

            // Delete entry from sale table
            $sqlDeleteSale = "DELETE FROM sale WHERE id = :sale_id";
            $stmtDeleteSale = $this->connection->prepare($sqlDeleteSale);
            $stmtDeleteSale->bindParam(':sale_id', $saleId, \PDO::PARAM_INT);
            $stmtDeleteSale->execute();

            $this->connection->commit();

            // Increment units_in_stock for each product in the sale
            foreach ($productIdQuantity as $item) {
                $this->incrementUnitsInStock($item['product_id'], $item['quantity']);
            }

            return true; // Deletion successful
        } catch (\PDOException $e) {
            $this->connection->rollBack();
            return false; // Deletion failed
        }
    }



    public function editProduct(
        string $id,
        string $name,
        string $brand,
        string $gender,
        string $type,
        string $material,
        float $weight_in_grams,
        string $description,
        float $selling_price,
        int $units_in_stock,
        string $image_url
    ): bool {



        $sql = "UPDATE product SET name = :name, brand = :brand, gender = :gender, type = :type, material = :material,
    weight_in_grams = :weight_in_grams, description = :description, selling_price = :selling_price,
    units_in_stock = :units_in_stock, image_url = :image_url WHERE id = :id";

        $stmt = $this->connection->prepare($sql);


        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':material', $material);
        $stmt->bindParam(':weight_in_grams', $weight_in_grams);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':selling_price', $selling_price);
        $stmt->bindParam(':units_in_stock', $units_in_stock);
        $stmt->bindParam(':image_url', $image_url);


        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            error_log("Error: " . $errorInfo[2]);
            return false;
        }
        return true;
    }



    public function editAccount(int $account_id, string $login, string $password, string $profile_pic_url): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE account SET login = :login, password = :password, profile_pic_url = :profile_pic_url WHERE id = :account_id";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':account_id', $account_id, \PDO::PARAM_INT);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':profile_pic_url', $profile_pic_url);

            if (!$stmt->execute()) {
                echo "error while editing account";
                return false;
            }

            echo "account edited successfully";
            return true;
        } catch (\PDOException $e) {
            echo "Exception: " . $e->getMessage();
            return false;
        }
    }



    public function getTotalProductsCount($user_id)
    {
        $total = 0;
        $sql = "SELECT COUNT(*) as total FROM product WHERE account_id = :user_id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt) {
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            $total = $data['total'];
        }

        return $total;
    }


    public function getTopProducts($accountId)
    {
        $sql = "SELECT p.name AS product_name, SUM(shp.quantity) AS total_quantity_sold
                FROM sale_has_product shp
                JOIN product p ON shp.product_id = p.id
                JOIN sale s ON shp.sale_id = s.id
                WHERE s.account_id = :account_id
                GROUP BY shp.product_id
                ORDER BY total_quantity_sold DESC
                LIMIT 5";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':account_id', $accountId, \PDO::PARAM_INT);
        $stmt->execute();

        $topProducts = $stmt->fetchAll(\PDO::FETCH_ASSOC);

       
        $formattedTopProducts = [];

        foreach ($topProducts as $topProduct) {
           
            $formattedTopProducts[] = [
                'name' => $topProduct['product_name'],
                'total_quantity_sold' => $topProduct['total_quantity_sold'],
            ];
        }

        return $formattedTopProducts;
    }



    public function getLowStockProducts($accountId)
    {
        $lowStockThreshold = 5;
        $sql = "SELECT name
            FROM product
            WHERE account_id = :account_id AND units_in_stock <= :low_stock_threshold";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':account_id', $accountId, \PDO::PARAM_INT);
        $stmt->bindParam(':low_stock_threshold', $lowStockThreshold, \PDO::PARAM_INT);
        $stmt->execute();

        $lowStockProducts = $stmt->fetchAll(\PDO::FETCH_ASSOC);



        $formattedLowStockProducts = [];

        foreach ($lowStockProducts as $lowStockProduct) {
            $formattedLowStockProducts[] = [
                'name' => $lowStockProduct['name'],

            ];
        }

        return $formattedLowStockProducts;
    }
}
